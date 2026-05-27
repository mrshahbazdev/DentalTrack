<?php

namespace App\Filament\Resources\ReworkEventResource\Pages;

use App\Filament\Resources\ReworkEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReworkEvent extends EditRecord
{
    protected static string $resource = ReworkEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
