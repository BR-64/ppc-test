<?php

namespace App\Filament\Resources\PCategoryResource\Pages;

use App\Filament\Resources\PCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPCategories extends ListRecords
{
    protected static string $resource = PCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
