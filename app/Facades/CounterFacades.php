<?php 

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CounterFacades extends Facade{
    public function getFacadeAccessors(){
        return 'App\Contracts\Counter';
    }
}