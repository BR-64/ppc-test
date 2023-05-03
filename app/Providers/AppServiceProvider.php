<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\pProduct;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $filterables = [
            'collection' => pProduct::distinct()->get('collection'),
            'category' => pProduct::distinct()->get('category'),
            'type' => pProduct::distinct()->get('type'),
            'brand' => pProduct::distinct()->get('brand_name'),
            'color' => pProduct::distinct()->get('color'),
            'finish' => pProduct::distinct()->get('finish'),
        ];

        View::share('sharedData', [
            'filterables'=>$filterables
        ]);

        // View::composer('*', function($view){
        //     $view->with('key', 'value');
        // });
        //
    }
}
