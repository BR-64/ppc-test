<?php

namespace App\Filament\Resources\ProductNewResource\Pages;

use App\Filament\Resources\ProductNewResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductNews extends ListRecords
{
    protected static string $resource = ProductNewResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
