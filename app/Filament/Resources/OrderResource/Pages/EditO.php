<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Http;

class EditO extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make()
            // ->requiresConfirmation(),
            
            Action::make('email')
            ->icon('heroicon-s-external-link')
            ->action(fn()=>redirect()-> route('admail_control')),

            // Action::make('payconfirm')
            // ->icon('heroicon-s-external-link')
            // ->url(fn (Order $record): string => route('mail.paycom', ['OrderID' =>$record]))
            // ->requiresConfirmation()
        ];
    }
}