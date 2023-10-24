<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['retail_price'] = number_format($data['retail_price']);
        $data['weight_g'] = number_format($data['weight_g']);
        $data['cubic_cm'] = number_format($data['cubic_cm']);

        return $data;
    }

    // protected function getRedirectUrl(): string 
    // { 
    //     return $this->getResource()::getUrl('index'); 
    // } 
}
