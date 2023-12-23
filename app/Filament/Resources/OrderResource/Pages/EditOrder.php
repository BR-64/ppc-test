<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Http;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->requiresConfirmation(),
            
            Action::make('email')
            ->icon('heroicon-s-external-link')
            ->action(fn()=>redirect()-> route('admail_control'))
            // ->requiresConfirmation()
        ];
    }
}
