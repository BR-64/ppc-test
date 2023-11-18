<?php

namespace App\Filament\Resources\ProductNewResource\Pages;

use App\Filament\Resources\ProductNewResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductNew extends EditRecord
{
    protected static string $resource = ProductNewResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
