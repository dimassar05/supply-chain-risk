<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil negara dari URL (misal: /api/risk?country=Germany)
        $country = $request->query('country', 'Germany'); // Defaultnya Germany

        // 2. Simulasi Data (Nanti ini diambil dari API cuaca, berita, dll)
        // Anggaplah kita sudah punya nilai risiko dari masing-masing indikator (skala 0-100)
        // Contoh untuk Germany:
        $simulatedData = [
            'weather_score' => 20,   // Risiko cuaca rendah
            'inflation_score' => 40, // Inflasi lumayan
            'news_score' => 10,      // Berita kebanyakan positif/netral
            'currency_score' => 30,  // Fluktuasi mata uang biasa saja
        ];

        // 3. Algoritma Risk Scoring Engine (Berdasarkan PDF Dosen)
        // Bobot: Cuaca 30%, Inflasi 20%, Berita 40%, Mata Uang 10%
        $weightWeather = 0.30;
        $weightInflation = 0.20;
        $weightNews = 0.40;
        $weightCurrency = 0.10;

        // 4. Kalkulasi Total Risiko
        $totalRisk = 
            ($simulatedData['weather_score'] * $weightWeather) +
            ($simulatedData['inflation_score'] * $weightInflation) +
            ($simulatedData['news_score'] * $weightNews) +
            ($simulatedData['currency_score'] * $weightCurrency);

        // Bulatkan nilainya
        $totalRisk = round($totalRisk);

        // 5. Tentukan Status Risiko
        $riskStatus = 'Low Risk';
        if ($totalRisk >= 70) {
            $riskStatus = 'High Risk';
        } elseif ($totalRisk >= 40) {
            $riskStatus = 'Medium Risk';
        }

        // 6. Kembalikan respons dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Skor risiko berhasil dihitung',
            'country' => $country,
            'data' => [
                'components' => [
                    'weather_risk' => $simulatedData['weather_score'],
                    'inflation_risk' => $simulatedData['inflation_score'],
                    'political_news_risk' => $simulatedData['news_score'],
                    'currency_risk' => $simulatedData['currency_score'],
                ],
                'calculation' => [
                    'formula' => 'Weather(30%) + Inflation(20%) + News(40%) + Currency(10%)',
                    'total_risk_score' => $totalRisk,
                    'risk_status' => $riskStatus
                ]
            ]
        ], 200);
    }
}