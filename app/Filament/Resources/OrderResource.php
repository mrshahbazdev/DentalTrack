<?php

namespace App\Filament\Resources;

use App\Enums\OrderPriority;
use App\Enums\OrderStatus;
use App\Enums\StepStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Company;
use App\Models\Lab;
use App\Models\Order;
use App\Models\ProductType;
use App\Services\StickerPdfService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationGroup(): ?string
    {
        return __('app.resource.production');
    }

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Auftragsdetails')->schema([
                Forms\Components\Select::make('company_id')
                    ->options(fn () => Company::pluck('name', 'id'))
                    ->required()
                    ->live()
                    ->placeholder(__('app.common.company'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        return Company::create($data)->getKey();
                    }),
                Forms\Components\Select::make('lab_id')
                    ->options(fn (Forms\Get $get) => Lab::where('company_id', $get('company_id'))->pluck('name', 'id'))
                    ->required()
                    ->placeholder(__('app.common.lab')),
                Forms\Components\Select::make('product_type_id')
                    ->options(fn (Forms\Get $get) => ProductType::where('company_id', $get('company_id'))->pluck('name', 'id'))
                    ->required()
                    ->placeholder(__('app.common.product_type')),
                Forms\Components\TextInput::make('patient_ref')
                    ->maxLength(255),
                Forms\Components\TextInput::make('doctor_name')
                    ->maxLength(255),
                Forms\Components\Select::make('priority')
                    ->options(OrderPriority::class)
                    ->default(OrderPriority::Normal)
                    ->required(),
                Forms\Components\DatePicker::make('due_date'),
                Forms\Components\Select::make('status')
                    ->options(OrderStatus::class)
                    ->default(OrderStatus::Pending)
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Auftrags-Nr.')
                    ->prefix('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('productType.name')
                    ->label('Produkt')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient_ref')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('doctor_name')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('lab.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (OrderPriority $state): string => $state->color()),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (OrderStatus $state): string => $state->color()),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->color(fn (Order $record): string => $record->isOverdue() ? 'danger' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name'),
                Tables\Filters\SelectFilter::make('lab')
                    ->relationship('lab', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options(OrderStatus::class),
                Tables\Filters\SelectFilter::make('priority')
                    ->options(OrderPriority::class),
                Tables\Filters\Filter::make('overdue')
                    ->query(fn (Builder $query): Builder => $query->where('due_date', '<', now())->whereNotIn('status', ['completed', 'cancelled']))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_sticker')
                    ->label('Aufkleber')
                    ->icon('heroicon-o-printer')
                    ->action(fn (Order $record) => app(StickerPdfService::class)->generateOrderSticker($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Auftragsinformationen')->schema([
                Infolists\Components\TextEntry::make('id')
                    ->label('Auftrags-Nr.')
                    ->prefix('#'),
                Infolists\Components\TextEntry::make('productType.name')
                    ->label('Produkttyp'),
                Infolists\Components\TextEntry::make('patient_ref'),
                Infolists\Components\TextEntry::make('doctor_name'),
                Infolists\Components\TextEntry::make('lab.name'),
                Infolists\Components\TextEntry::make('priority')
                    ->badge()
                    ->color(fn (OrderPriority $state): string => $state->color()),
                Infolists\Components\TextEntry::make('status')
                    ->badge()
                    ->color(fn (OrderStatus $state): string => $state->color()),
                Infolists\Components\TextEntry::make('due_date')
                    ->date(),
                Infolists\Components\TextEntry::make('qr_code')
                    ->label('QR-Code UUID'),
                Infolists\Components\TextEntry::make('notes'),
            ])->columns(3),

            Infolists\Components\Section::make('Produktionsschritte')->schema([
                Infolists\Components\RepeatableEntry::make('steps')
                    ->schema([
                        Infolists\Components\TextEntry::make('sort_order')
                            ->label('#'),
                        Infolists\Components\TextEntry::make('step_name'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (StepStatus $state): string => $state->color()),
                        Infolists\Components\TextEntry::make('assignedUser.name')
                            ->label('Zugewiesen an')
                            ->default('-'),
                    ])
                    ->columns(4),
            ]),

            Infolists\Components\Section::make('Scan-Verlauf')->schema([
                Infolists\Components\RepeatableEntry::make('scanEvents')
                    ->schema([
                        Infolists\Components\TextEntry::make('scanned_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('event_type')
                            ->badge(),
                        Infolists\Components\TextEntry::make('workstation.name'),
                        Infolists\Components\TextEntry::make('user.name'),
                        Infolists\Components\TextEntry::make('duration_seconds')
                            ->label('Dauer')
                            ->formatStateUsing(fn (?int $state): string => $state ? gmdate('H:i:s', $state) : '-'),
                    ])
                    ->columns(5),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
