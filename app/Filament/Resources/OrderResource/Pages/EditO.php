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
            
            // Action::make('Cancel Order') 
            // ->requiresConfirmation()
            // ->icon('heroicon-s-external-link')
            // // ->form([
            // //         TextInput::make('id')
            // //         ->label('Order Id')
            // //         ->required(),
            // //     ])
            // ->action(function (){
            //     $jsonData = [
            //         '_token'=>csrf_token(),
            //         // 'OrderID' => $data['id'],
            //         // 'OrderID' => $this->record->id,
            //         'OrderID' => 279,
            //      ];

            //      $url= route('cancelOrder');
            //     // $response = Http::post($url,$jsonData);
            //     // $response = Http::post($url,['OrderID' => 279]);
            //     $response = Http::asForm()->post($url,['OrderID' => 279]);
            //     // $response = Http::post($url);
            // // $response = Http::withToken('token')->post($url,['OrderID'=> 279]);
            
            // }),

            // Action::make('settings')
            //     ->label('Settings')
            //     ->action('openSettingsModal'),
            
            Action::make('Admin Control')
            ->icon('heroicon-s-external-link')
            ->action(fn()=>redirect()-> route('admail_control')),

            // Action::make('payconfirm')
            // ->icon('heroicon-s-external-link')
            // ->url(fn (Order $record): string => route('mail.paycom', ['OrderID' =>$record]))
            // ->requiresConfirmation()
        ];
    }
}