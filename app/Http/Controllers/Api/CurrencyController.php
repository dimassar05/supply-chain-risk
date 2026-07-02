<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil parameter base currency dari URL (misal: /api/currency?base=USD)
        $base = $request->query('base', 'USD'); // Defaultnya adalah USD

        // 2. Simulasi Data Kurs (ExchangeRate API)
        // Untuk tahap awal development, kita pakai data statis dulu agar tidak terkena limit API.
        // Nanti saat integrasi penuh, kita bisa ganti menggunakan Http::get('https://api.exchangerate-api.com/...');
        $dummyRates = [
            'USD' => 1.00,
            'EUR' => 0.92,
            'CNY' => 7.24,
            'IDR' => 16250.00,
            'AUD' => 1.52,
            'JPY' => 155.40,
            'GBP' => 0.79
        ];

        // 3. Kembalikan respons dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Data kurs mata uang berhasil diambil',
            'base_currency' => $base,
            'rates' => $dummyRates,
            'last_updated' => now()->toDateTimeString()
        ], 200);
    }
}