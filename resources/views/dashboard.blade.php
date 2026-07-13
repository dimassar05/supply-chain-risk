@extends('layouts.app')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Styling Panel Global */
    .dashboard-panel {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        height: 100%;
    }
    
    .panel-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
        letter-spacing: -0.2px;
    }

    /* =========================================================
       PERBAIKAN KPI CARDS (DIJAMIN TIDAK TERPOTONG/CLIPPING)
       ========================================================= */
    .kpi-card {
        background: #ffffff;
        border-radius: 16px;
        /* Padding dikurangi agar area teks menjadi lebih lega */
        padding: 16px 20px; 
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        /* Gap diperkecil agar teks tidak terdorong */
        gap: 14px; 
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .kpi-card:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); 
        border-color: #cbd5e1;
    }
    
    .kpi-icon {
        /* Ikon sedikit diperkecil untuk memberi ruang ekstra ke teks */
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0; 
    }

    .kpi-data {
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow: hidden;
        width: 100%; /* Memaksa elemen mengisi sisa ruang yang ada */
    }

    .kpi-data span { 
        font-size: 11px; 
        font-weight: 600; 
        color: #64748b; 
        text-transform: uppercase; 
        letter-spacing: 0.5px;
        margin-bottom: 2px;
        white-space: nowrap; 
        overflow: hidden;
        text-overflow: ellipsis; /* Akan menjadi '...' jika sangat sempit */
    }

    .kpi-data h4 { 
        font-size: 22px; /* Ukuran font disesuaikan */
        font-weight: 800; 
        margin: 0; 
        color: #0f172a; 
        letter-spacing: -0.5px;
        white-space: nowrap; 
        overflow: hidden;
        text-overflow: ellipsis; /* Akan menjadi '...' jika sangat sempit */
    }

    /* Styling Map & Chart */
    #overviewMap { height: 400px; border-radius: 12px; z-index: 1; border: 1px solid #e2e8f0; }
    .chart-wrapper { height: 400px; width: 100%; }
</style>

<div class="container-fluid p-0 pb-4">
    
    <!-- HEADER: JUDUL & DROPDOWN FILTER -->
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="fw-bold text-dark mb-1" style="font-size: 32px; letter-spacing: -1px;">Dashboard</h1>
            <p class="text-muted mb-0 fs-6">Monitoring Risiko Rantai Pasok Global</p>
        </div>
        <div class="d-flex flex-column" style="min-width: 260px;">
            <label for="countryFilter" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase;">Pilih Negara Target</label>
            <div class="input-group shadow-sm border-0 rounded-3">
                <span class="input-group-text bg-white border-end-0 px-3"><i class="fa-solid fa-earth-americas text-primary"></i></span>
                <select id="countryFilter" class="form-select border-start-0 fw-semibold py-2" style="cursor: pointer; font-size: 14.5px;">
                    <option value="Global" selected>Global (Semua Negara)</option>
                    <option value="Germany">Germany (DE)</option>
                    <option value="Indonesia">Indonesia (ID)</option>
                    <option value="United States">United States (US)</option>
                    <option value="China">China (CN)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ROW 1: KPI CARDS -->
    <div class="row g-3 mb-4">
        <!-- GDP -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-money-bill-wave"></i></div>
                <div class="kpi-data">
                    <span>Est. GDP</span>
                    <h4 id="kpiGdp">$ 105.4 T</h4>
                </div>
            </div>
        </div>
        <!-- Inflation -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-arrow-trend-up"></i></div>
                <div class="kpi-data">
                    <span>Avg Inflation</span>
                    <h4 id="kpiInflation">5.2%</h4>
                </div>
            </div>
        </div>
        <!-- Exchange Rate -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon bg-info bg-opacity-10 text-info"><i class="fa-solid fa-money-bill-transfer"></i></div>
                <div class="kpi-data">
                    <span>Base Currency</span>
                    <h4 id="kpiCurrency">USD</h4>
                </div>
            </div>
        </div>
        <!-- Risk Score -->
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon bg-danger bg-opacity-10 text-danger"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="kpi-data">
                    <span>Risk Score</span>
                    <h4 id="kpiRisk">42%</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2: PETA & GRAFIK TREN -->
    <div class="row g-4 mb-4">
        <!-- Peta Global -->
        <div class="col-xl-7 col-lg-12">
            <div class="dashboard-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="panel-title mb-0">Global Risk Map</h6>
                    <span class="badge bg-light text-dark border px-3 py-1 rounded-pill"><i class="fa-solid fa-satellite-dish text-primary me-1"></i> Peta Interaktif</span>
                </div>
                <div id="overviewMap"></div>
            </div>
        </div>
        
        <!-- Grafik Tren -->
        <div class="col-xl-5 col-lg-12">
            <div class="dashboard-panel">
                <h6 class="panel-title">Risk Trend (6 Months)</h6>
                <div class="chart-wrapper">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 3: RISK SUMMARY & LATEST INTELLIGENCE -->
    <div class="row g-4">
        <!-- Risk Summary -->
        <div class="col-xl-6 col-lg-12">
            <div class="dashboard-panel">
                <h6 class="panel-title mb-4">Risk Summary</h6>
                
                <div class="p-4 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25 mb-4">
                    <div class="d-flex gap-3 align-items-start">
                        <i class="fa-solid fa-robot text-primary mt-1 fs-3"></i>
                        <div>
                            <h6 class="fw-bold text-dark mb-2 fs-5" id="summaryTitle">AI Analysis: Global Condition</h6>
                            <p class="text-muted mb-0" style="line-height: 1.6; font-size: 14px;" id="summaryText">
                                Kondisi rantai pasok secara global saat ini berada dalam tingkat risiko <strong>Menengah (Medium)</strong>. Tekanan inflasi di kawasan Eropa dan fluktuasi nilai tukar dolar menjadi faktor utama penyumbang risiko. Jalur maritim terpantau stabil setelah terurainya antrean kapal di rute utama.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 px-2">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2"><span class="fw-semibold text-muted" style="font-size: 13px;">Risiko Inflasi & Ekonomi</span><span class="fw-bold text-dark">65%</span></div>
                        <div class="progress rounded-pill" style="height: 8px;"><div class="progress-bar bg-warning rounded-pill" style="width: 65%"></div></div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2"><span class="fw-semibold text-muted" style="font-size: 13px;">Risiko Cuaca Alam</span><span class="fw-bold text-dark">20%</span></div>
                        <div class="progress rounded-pill" style="height: 8px;"><div class="progress-bar bg-success rounded-pill" style="width: 20%"></div></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-2"><span class="fw-semibold text-muted" style="font-size: 13px;">Sentimen Berita Politik</span><span class="fw-bold text-dark">45%</span></div>
                        <div class="progress rounded-pill" style="height: 8px;"><div class="progress-bar bg-info rounded-pill" style="width: 45%"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Intelligence Feed -->
        <div class="col-xl-6 col-lg-12">
            <div class="dashboard-panel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="panel-title mb-0">Latest Intelligence</h6>
                    <button class="btn btn-sm btn-outline-secondary fw-semibold px-3 rounded-pill" style="font-size: 12px;">Lihat Semua</button>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 bg-danger bg-opacity-10 rounded-3 border-start border-danger border-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-danger">High Risk</span>
                            <small class="text-muted fw-semibold" style="font-size: 11px;"><i class="fa-regular fa-clock me-1"></i> 10 min ago</small>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Strike at Port of Hamburg</h6>
                        <p class="mb-0 text-muted" style="font-size: 13px;">Aksi mogok pekerja pelabuhan diprediksi menunda pengiriman kargo ke Asia hingga 48 jam ke depan.</p>
                    </div>
                    
                    <div class="p-3 bg-warning bg-opacity-10 rounded-3 border-start border-warning border-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-warning text-dark">Medium Risk</span>
                            <small class="text-muted fw-semibold" style="font-size: 11px;"><i class="fa-regular fa-clock me-1"></i> 2 hrs ago</small>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Eurozone Inflation Fluctuation</h6>
                        <p class="mb-0 text-muted" style="font-size: 13px;">Data terbaru menunjukkan inflasi meningkat, memberikan tekanan pada nilai tukar EUR terhadap USD.</p>
                    </div>

                    <div class="p-3 bg-success bg-opacity-10 rounded-3 border-start border-success border-4 mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-success">Resolved</span>
                            <small class="text-muted fw-semibold" style="font-size: 11px;"><i class="fa-regular fa-clock me-1"></i> 5 hrs ago</small>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Suez Canal Traffic Normal</h6>
                        <p class="mb-0 text-muted" style="font-size: 13px;">Kemacetan rute logistik telah terurai, jadwal kapal kargo mulai kembali normal hari ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. SETUP MAP
        const map = L.map('overviewMap').setView([20, 0], 2); 
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; CARTO' }).addTo(map);

        const countryData = {
            'Global': { coords: [20, 0], zoom: 2, gdp: '$ 105.4 T', inf: '5.2%', cur: 'USD', risk: '42%' },
            'Germany': { coords: [51.1657, 10.4515], zoom: 5, gdp: '$ 4.2 T', inf: '2.4%', cur: 'EUR', risk: '25%' },
            'Indonesia': { coords: [-0.7893, 113.9213], zoom: 5, gdp: '$ 1.3 T', inf: '3.1%', cur: 'IDR', risk: '38%' },
            'United States': { coords: [37.0902, -95.7129], zoom: 4, gdp: '$ 26.9 T', inf: '3.7%', cur: 'USD', risk: '30%' },
            'China': { coords: [35.8617, 104.1954], zoom: 4, gdp: '$ 17.7 T', inf: '0.1%', cur: 'CNY', risk: '45%' }
        };

        // Letakkan pin dummy
        Object.keys(countryData).forEach(key => {
            if(key !== 'Global') L.marker(countryData[key].coords).addTo(map).bindTooltip(key);
        });

        // 2. SETUP CHART
        const ctx = document.getElementById('trendChart').getContext('2d');
        let trendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Risk Score',
                    data: [35, 38, 45, 42, 50, 42],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3, fill: true, tension: 0.4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, max: 100, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 3. LOGIKA FILTER NEGARA
        const countryFilter = document.getElementById('countryFilter');
        countryFilter.addEventListener('change', function() {
            const selected = this.value;
            const data = countryData[selected];
            
            // Animasi Peta
            map.flyTo(data.coords, data.zoom, { duration: 1.5 });

            // Ubah Angka KPI
            document.getElementById('kpiGdp').innerText = data.gdp;
            document.getElementById('kpiInflation').innerText = data.inf;
            document.getElementById('kpiCurrency').innerText = data.cur;
            document.getElementById('kpiRisk').innerText = data.risk;
            document.getElementById('summaryTitle').innerText = `AI Analysis: ${selected === 'Global' ? 'Global' : selected} Condition`;
            
            // Ubah Grafik secara random untuk simulasi
            trendChart.data.datasets[0].data = Array.from({length: 6}, () => Math.floor(Math.random() * 40) + (selected === 'Global' ? 30 : 20));
            trendChart.update();
        });
    });
</script>