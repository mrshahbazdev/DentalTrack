<?php

namespace App\Filament\Resources;

use App\Enums\WorkstationType;
use App\Filament\Resources\WorkstationResource\Pages;
use App\Models\Workstation;
use App\Services\StickerPdfService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class WorkstationResource extends Resource
{
    protected static ?string $model = Workstation::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    public static function getNavigationGroup(): ?string
    {
        return __('app.resource.production');
    }

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Arbeitsstationsdetails')->schema([
                Forms\Components\Select::make('lab_id')
                    ->relationship('lab', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder(__('app.common.lab'))
                    ->createOptionForm([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ]),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options(WorkstationType::class)
                    ->required()
                    ->default(WorkstationType::Station),
                Forms\Components\TextInput::make('qr_code')
                    ->default(fn () => 'WS-'.Str::ulid())
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lab.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (WorkstationType $state): string => match ($state) {
                        WorkstationType::Station => 'info',
                        WorkstationType::WaitingArea => 'warning',
                    }),
                Tables\Columns\TextColumn::make('qr_code')
                    ->limit(20)
                    ->tooltip(fn (Workstation $record): string => $record->qr_code),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('lab')
                    ->relationship('lab', 'name'),
                Tables\Filters\SelectFilter::make('type')
                    ->options(WorkstationType::class),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('regenerate_qr')
                    ->label('QR neu generieren')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(fn (Workstation $record) => $record->update(['qr_code' => 'WS-'.Str::ulid()])),
                Tables\Actions\Action::make('download_sticker')
                    ->label('Aufkleber herunterladen')
                    ->icon('heroicon-o-printer')
                    ->action(fn (Workstation $record) => app(StickerPdfService::class)->generateWorkstationSticker($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkstations::route('/'),
            'create' => Pages\CreateWorkstation::route('/create'),
            'edit' => Pages\EditWorkstation::route('/{record}/edit'),
        ];
    }
}
