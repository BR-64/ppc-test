<?php

namespace App\Filament\Resources\BoxInfoResource\Pages;

use App\Filament\Resources\BoxInfoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBoxInfos extends ListRecords
{
    protected static string $resource = BoxInfoResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Actions\Action::make('test box calculation')
                // ->label('test box calculation')
                ->icon('heroicon-s-external-link')
                ->action(fn()=>redirect()-> route('box_cal_test')),
        ];
    }
}
