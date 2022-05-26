<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

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
        Validator::extend('without_spaces', function($attribute, $value) {
            return preg_match('/^\S*$/u', $value);
        });
        Validator::extend('person_name', function($attribute, $value) {
            return preg_match('/^[\pL\s\-]+$/u', $value);
        });

        JsonResource::withoutWrapping();
    }
}
