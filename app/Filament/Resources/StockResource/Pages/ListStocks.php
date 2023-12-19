<?php

namespace App\Filament\Resources\StockResource\Pages;

use App\Filament\Resources\StockResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListStocks extends ListRecords
{
    protected static string $resource = StockResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('update Stock from Enpro')
            ->icon('heroicon-s-cog')
            ->label('update Stock from Enpro')
            ->action(fn()=>redirect()-> route('product.updatestock_enpro'))
            ->requiresConfirmation()
        ];
    }
}
