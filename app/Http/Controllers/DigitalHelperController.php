<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DigitalHelperController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function anyRoute(Request $request)
    {
        return view('login');
    }
}
