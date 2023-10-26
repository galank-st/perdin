<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $data['judul'] = "Dashboard";
        $data['sub_judul'] = "Dashboard";
        return view('dashboard', $data);
    }
}
