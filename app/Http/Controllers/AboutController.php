<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function __invoke()// use when there's only one single action 
    // must be named __invoke()
    {
        return 'Single';
    }
}
