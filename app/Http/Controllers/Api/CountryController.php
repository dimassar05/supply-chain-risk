<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country; // Memanggil model Country
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel countries
        $countries = Country::all();

        // Mengembalikan respon dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Daftar Negara Berhasil Diambil',
            'data'    => $countries
        ], 200);
    }
}