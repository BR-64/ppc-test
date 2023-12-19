<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('get Data from Enpro')
            ->icon('heroicon-s-cog')
            ->action(fn()=>redirect()-> route('product.getalldata'))
            ->requiresConfirmation()
        ];
    }

    protected function mutateTableDataBeforeFill(array $data): array
    {
        $data['retail_price'] = number_format($data['retail_price']);
 
        return $data;
    }
}
