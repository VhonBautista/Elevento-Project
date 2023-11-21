<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function index()
    {
        $campus = session('campus');

        return view('preview');
    }
}
