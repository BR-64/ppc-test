<?php

namespace App\Filament\Resources\PCategoryResource\Pages;

use App\Filament\Resources\PCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPCategory extends EditRecord
{
    protected static string $resource = PCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
