<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    //

    public function index()
    {
        $signal = Signature::get();
        return view('pages.support_team.signed.index', compact('signal'));
    }
}
