<?php

namespace App\Filament\Resources\BoxbufferResource\Pages;

use App\Filament\Resources\BoxbufferResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBoxbuffers extends ListRecords
{
    protected static string $resource = BoxbufferResource::class;

    protected function getActions(): array
    {
        return [
                       Actions\Action::make('test box calculation')
                       ->icon('heroicon-s-external-link')
                       ->action(fn()=>redirect()-> route('box_cal_test')),
        ];
    }
}
