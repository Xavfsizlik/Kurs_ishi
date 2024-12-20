<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail(){
        $to="samariddinayxonov@gmail.com";
        $msg="Salom";
        $subject="Salomlar";
        Mail::to($to)->send(new WelcomeMail($msg,$subject));
    }
}
