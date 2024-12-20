<?php

namespace App\Http\Controllers;

use App\Models\Cipher;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about(){
        return view("about");
    }
}
