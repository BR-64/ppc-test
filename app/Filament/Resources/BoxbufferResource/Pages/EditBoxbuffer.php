<?php

namespace App\Filament\Resources\BoxbufferResource\Pages;

use App\Filament\Resources\BoxbufferResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBoxbuffer extends EditRecord
{
    protected static string $resource = BoxbufferResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
