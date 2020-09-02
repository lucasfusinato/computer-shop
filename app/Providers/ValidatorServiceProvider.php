<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Respect\Validation\Rules as RespectRules;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //CPF validator
        Validator::extend('cpf', function($attribute, $value, $parameters, $validator) {
            return (new RespectRules\Cpf())->validate($value);
        });

        //CEP validator
        Validator::extend('cep', function($attribute, $value, $parameters, $validator) {
            return (new RespectRules\PostalCode('BR'))->validate($value);
        });
    }
}
