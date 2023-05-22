<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $data['title'] = "Dashboard";
        $data['sub_title'] = "Dashboard";
        return view('dashboard', $data);
    }
}
