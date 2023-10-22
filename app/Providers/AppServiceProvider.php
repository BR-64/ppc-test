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
        // \URL::forceRootUrl(\Config::get('app.url'));    
        // if (str_contains(\Config::get('app.url'), 'https://')) {
        //     \URL::forceScheme('https');

        // if($this->app->environment('production')) {
        //     \URL::forceScheme('https');
        // }

        $filterables = [
            'collection' => pProduct::distinct()->get(['collection']),
            // 'collection' => pProduct::collection->get(),
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
