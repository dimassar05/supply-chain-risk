<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Port; // Memanggil model Port
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index()
    {
        // Mengambil semua data pelabuhan dari database
        $ports = Port::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Pelabuhan Berhasil Diambil',
            'data'    => $ports
        ], 200);
    }
}