<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComboController extends Controller
{
    public function index()
    {
        return view('combo'); // Pastikan ada file combo.blade.php di resources/views
    }
}