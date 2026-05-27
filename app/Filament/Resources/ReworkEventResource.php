<?php

namespace App\Filament\Resources;

use App\Enums\ReworkCause;
use App\Enums\ReworkStatus;
use App\Filament\Resources\ReworkEventResource\Pages;
use App\Models\ReworkEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReworkEventResource extends Resource
{
    protected static ?string $model = ReworkEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Quality Control';

    protected static ?string $navigationLabel = 'Rework Events';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('order_id')
                ->relationship('order', 'id')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\Select::make('order_step_id')
                ->relationship('orderStep', 'step_name')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\Select::make('flagged_by')
                ->relationship('flaggedByUser', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\Select::make('original_technician')
                ->relationship('originalTechnician', 'name')
                ->nullable()
                ->searchable()
                ->preload(),
            Forms\Components\Select::make('cause')
                ->options(collect(ReworkCause::cases())->mapWithKeys(fn (ReworkCause $c) => [$c->value => $c->label()]))
                ->required(),
            Forms\Components\Textarea::make('description')
                ->maxLength(1000),
            Forms\Components\Select::make('status')
                ->options(collect(ReworkStatus::cases())->mapWithKeys(fn (ReworkStatus $s) => [$s->value => $s->label()]))
                ->default('pending')
                ->required(),
            Forms\Components\Select::make('resolved_by')
                ->relationship('resolvedByUser', 'name')
                ->nullable()
                ->searchable()
                ->preload(),
            Forms\Components\DateTimePicker::make('resolved_at')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('order.id')
                    ->label('Order #')
                    ->formatStateUsing(fn ($state): string => "#{$state}")
                    ->sortable(),
                Tables\Columns\TextColumn::make('orderStep.step_name')
                    ->label('Step')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cause')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state instanceof ReworkCause ? $state->label() : (string) $state)
                    ->color(fn ($state): string => $state instanceof ReworkCause ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state instanceof ReworkStatus ? $state->label() : (string) $state)
                    ->color(fn ($state): string => $state instanceof ReworkStatus ? $state->color() : 'gray'),
                Tables\Columns\TextColumn::make('flaggedByUser.name')
                    ->label('Flagged By'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('cause')
                    ->options(collect(ReworkCause::cases())->mapWithKeys(fn (ReworkCause $c) => [$c->value => $c->label()])),
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(ReworkStatus::cases())->mapWithKeys(fn (ReworkStatus $s) => [$s->value => $s->label()])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReworkEvents::route('/'),
            'create' => Pages\CreateReworkEvent::route('/create'),
            'edit' => Pages\EditReworkEvent::route('/{record}/edit'),
        ];
    }
}
