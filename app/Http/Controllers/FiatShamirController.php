<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PrimeNumberGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FiatShamirController extends Controller
{
    public function index()
    {
        // Blade faylni ko'rsatish
        return view('fiatshamir');
    }

    public function process(Request $request)
    {
        // $result = PrimeNumberGenerator::FiatShamir();
        // return redirect()->route('fiat-shamir.index')->with('result', $result);
    }
}
