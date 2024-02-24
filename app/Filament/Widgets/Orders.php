<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class Orders extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        // ...
        return Order::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                    ->searchable(),
            TextColumn::make('created_at')
                ->dateTime(),
            TextColumn::make('status')

            // ...
        ];
    }
}
