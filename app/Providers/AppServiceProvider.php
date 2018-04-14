<?php

namespace App\Providers;

use App\Serializers\RESTSerializer;
use Dingo\Api\Transformer\Adapter\Fractal;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function () {
            $fractal = new Manager();
            $fractal->setSerializer(new RESTSerializer());
            return new Fractal($fractal, 'include', ',');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
