<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
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
            // Actions\DeleteAction::make()
            // ->requiresConfirmation(),
            
            // Action::make('email')
            // ->icon('heroicon-s-external-link')
            // ->action(fn()=>redirect()-> route('admail_control')),

            // Action::make('payconfirm')
            // ->icon('heroicon-s-external-link')
            // ->url(fn (Order $record): string => route('mail.paycom', ['OrderID' =>$record]))
            // ->requiresConfirmation()

            // Action::make('Cancel Order')
            //     ->requiresConfirmation()
            //     ->icon('heroicon-s-external-link')
                // ->action(function (Order $order){
                //     $jsonData = [
                //         '_token'=>csrf_token(),
                //         'OrderID' => $trans_id,
                //      ];

                     
                //     Http::get(route('admail_control'));
                // })
        ];
    }
}