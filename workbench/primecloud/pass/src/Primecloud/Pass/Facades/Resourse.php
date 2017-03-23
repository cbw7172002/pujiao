<?php

namespace Primecloud\Pass\Facades;

use Illuminate\Support\Facades\Facade;

class Resourse extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'recourse';
    }
}