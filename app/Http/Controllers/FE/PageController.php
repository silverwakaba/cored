<?php

namespace App\Http\Controllers\FE;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class PageController extends Controller{
    // Index
    public function index(){
        return view('pages/blank');
    }
}
