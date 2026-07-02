<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PositiveWord;
use App\Models\NegativeWord;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil keyword dari URL (misal: /api/news?q=germany economy)
        $query = $request->query('q', 'logistics'); // default pencarian 'logistics'

        // 2. Simulasi Data Berita (Karena GNews API butuh API Key, untuk saat ini kita pakai data dummy dulu. Nanti bisa diganti dengan cURL/Http Request ke GNews)
        // Saya buatkan teks berita dummy yang mengandung kata-kata dari kamus kita (growth, increase, war, inflation, dll)
        $dummyArticles = [
            [
                'title' => 'Economic Update',
                'description' => 'Inflation increases while exports decrease due to war. The delay in shipping causes a disaster.',
                'url' => 'https://example.com/news/1',
                'published_at' => now()->toDateString()
            ],
            [
                'title' => 'Trade Route Open',
                'description' => 'The economy shows stable growth. Profit is expected to increase and improve recovery this year.',
                'url' => 'https://example.com/news/2',
                'published_at' => now()->toDateString()
            ]
        ];

        // 3. Ambil Kamus Kata dari Database (diubah menjadi array biasa)
        $positiveDict = PositiveWord::pluck('word')->toArray();
        $negativeDict = NegativeWord::pluck('word')->toArray();

        $analyzedNews = [];

        // 4. Proses Analisis Sentimen (Lexicon Based) - Sesuai algoritma dosen
        foreach ($dummyArticles as $article) {
            // Ubah teks berita jadi huruf kecil semua dan hapus tanda baca
            $cleanText = strtolower(preg_replace('/[^a-zA-Z\s]/', '', $article['description']));
            
            // Pecah kalimat menjadi array kata-kata
            $words = explode(' ', $cleanText);
            
            $positiveScore = 0;
            $negativeScore = 0;
            
            $foundPositiveWords = [];
            $foundNegativeWords = [];

            // Cocokkan setiap kata di berita dengan kamus
            foreach ($words as $word) {
                // Hilangkan spasi berlebih
                $word = trim($word);
                if (empty($word)) continue;

                if (in_array($word, $positiveDict)) {
                    $positiveScore++;
                    $foundPositiveWords[] = $word;
                }
                
                if (in_array($word, $negativeDict)) {
                    $negativeScore++;
                    $foundNegativeWords[] = $word;
                }
            }

            // Tentukan hasil akhir sentimen
            $sentiment = "Neutral";
            if ($positiveScore > $negativeScore) {
                $sentiment = "Positive";
            } elseif ($negativeScore > $positiveScore) {
                $sentiment = "Negative";
            }

            // Hitung persentase (Opsional, agar mirip seperti contoh di PDF)
            $totalScore = $positiveScore + $negativeScore;
            $positivePercentage = $totalScore > 0 ? round(($positiveScore / $totalScore) * 100) : 0;
            $negativePercentage = $totalScore > 0 ? round(($negativeScore / $totalScore) * 100) : 0;

            // Masukkan hasil analisis ke array baru
            $analyzedNews[] = [
                'title' => $article['title'],
                'description' => $article['description'],
                'url' => $article['url'],
                'analysis' => [
                    'sentiment' => $sentiment,
                    'positive_score' => $positiveScore,
                    'negative_score' => $negativeScore,
                    'positive_percentage' => $positivePercentage . '%',
                    'negative_percentage' => $negativePercentage . '%',
                    'words_detected' => [
                        'positive' => array_unique($foundPositiveWords),
                        'negative' => array_unique($foundNegativeWords)
                    ]
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil diambil dan dianalisis',
            'query' => $query,
            'data' => $analyzedNews
        ], 200);
    }
}