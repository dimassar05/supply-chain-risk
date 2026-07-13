<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil semua negara untuk ditampilkan di pilihan (dropdown)
        $countries = Country::all();
        
        // Mengirim data negara ke tampilan dashboard.blade.php
        return view('dashboard', compact('countries'));
    }
}