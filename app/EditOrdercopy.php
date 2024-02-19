<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Http;

class EditOrderrr extends EditRecord
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

            // Action::make('Cancel Order')
            //     ->requiresConfirmation()
            //     ->icon('heroicon-s-external-link')
            //     ->action(function (Order $order){
            //         $jsonData = [
            //             '_token'=>csrf_token(),
            //             'OrderID' => $trans_id,
            //          ];

                     
            //         Http::get(route('admail_control'))),
            //     }
                // ->action(Http::post(route('cancelOrder')))
                // ->action(Http::post(route('cancelOrder'),[
                //     ''
                // ]))
                // ->form([
                //     TextInput::make('id')
                //     ->label('Order Id')
                //     ->required(),
                // ]),

            // Action::make('payconfirm')
            // ->icon('heroicon-s-external-link')
            // ->url(fn (Order $record): string => route('mail.paycom', ['OrderID' =>$record]))
            // ->requiresConfirmation()
        ];
    }
}
