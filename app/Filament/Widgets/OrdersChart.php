<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;

class OrdersChart extends LineChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }
}
