<?php

namespace App\Filament\Resources\BoxInfoResource\Pages;

use App\Filament\Resources\BoxInfoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBoxInfo extends EditRecord
{
    protected static string $resource = BoxInfoResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
