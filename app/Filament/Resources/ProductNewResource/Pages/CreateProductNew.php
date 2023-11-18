<?php

namespace App\Filament\Resources\ProductNewResource\Pages;

use App\Filament\Resources\ProductNewResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductNew extends CreateRecord
{
    protected static string $resource = ProductNewResource::class;
}
