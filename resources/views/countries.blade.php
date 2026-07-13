@extends('layouts.app')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .dashboard-panel {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #f1f5f9;
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

    /* Profil Negara Card */
    .country-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .flag-container {
        width: 80px;
        height: 56px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .flag-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .country-name {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .country-region {
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        margin: 0;
    }

    /* List Data Detail */
    .detail-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .detail-item {
        background-color: #f8fafc;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid #f1f5f9;
    }

    .detail-label {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }

    #countryMap { 
        height: 380px; 
        border-radius: 12px; 
        z-index: 1; 
        border: 1px solid #e2e8f0;
    }

    .progress {
        background-color: #f1f5f9;
        border-radius: 10px;
        height: 10px;
    }
</style>

<div class="container-fluid p-0">
    
    <!-- HEADER: JUDUL & DROPDOWN FILTER -->
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="fw-bold text-dark mb-1" style="font-size: 28px; letter-spacing: -1px;">Countries Dashboard</h1>
            <p class="text-muted mb-0 fs-6">Analisis Mendalam Indikator Spesifik Per Negara</p>
        </div>
        <div class="d-flex flex-column" style="min-width: 280px;">
            <label for="countrySelector" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase;">Pilih Negara Analisis</label>
            <div class="input-group shadow-sm border-0 rounded-3">
                <span class="input-group-text bg-white border-end-0 px-3"><i class="fa-solid fa-magnifying-glass-location text-primary"></i></span>
                <select id="countrySelector" class="form-select border-start-0 fw-semibold py-2" style="cursor: pointer; font-size: 14.5px;">
                    <option value="ID" selected>Indonesia (ID)</option>
                    <option value="DE">Germany (DE)</option>
                    <option value="US">United States (US)</option>
                    <option value="CN">China (CN)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- BARIS 1, KOLOM 1: PROFIL NEGARA -->
        <div class="col-xl-6">
            <div class="dashboard-panel">
                <div class="country-header">
                    <div class="flag-container">
                        <img id="c_flag" src="https://flagcdn.com/w80/id.png" alt="Flag">
                    </div>
                    <div>
                        <h2 class="country-name" id="c_name">Indonesia</h2>
                        <p class="country-region" id="c_region"><i class="fa-solid fa-location-dot me-1"></i> Southeast Asia</p>
                    </div>
                    <div class="ms-auto text-end">
                        <div class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold" style="font-size: 14px;" id="c_status">
                            Medium Risk
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-3" style="font-size: 14px;">Informasi Demografi & Ekonomi Dasar</h6>
                <ul class="detail-list">
                    <li class="detail-item">
                        <div class="detail-label">Ibu Kota</div>
                        <div class="detail-value" id="c_capital">Jakarta</div>
                    </li>
                    <li class="detail-item">
                        <div class="detail-label">Populasi</div>
                        <div class="detail-value" id="c_pop">275 Juta</div>
                    </li>
                    <li class="detail-item">
                        <div class="detail-label">Mata Uang Lokal</div>
                        <div class="detail-value" id="c_cur">IDR (Rupiah)</div>
                    </li>
                    <li class="detail-item">
                        <div class="detail-label">Gross Domestic Product</div>
                        <div class="detail-value" id="c_gdp">$ 1.3 Triliun</div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- BARIS 1, KOLOM 2: RINCIAN SKOR RISIKO -->
        <div class="col-xl-6">
            <div class="dashboard-panel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="panel-title mb-0">Risk Score Breakdown</h6>
                    <h3 class="fw-bold text-dark mb-0" id="c_risk_total">38%</h3>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold text-muted" style="font-size: 13px;"><i class="fa-solid fa-cloud-bolt text-warning me-2"></i>Risiko Cuaca & Alam</span>
                        <span class="fw-bold text-dark" id="txt_risk_weather">20%</span>
                    </div>
                    <div class="progress"><div class="progress-bar bg-warning" id="bar_risk_weather" style="width: 20%"></div></div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold text-muted" style="font-size: 13px;"><i class="fa-solid fa-money-bill-trend-up text-danger me-2"></i>Risiko Makroekonomi (Inflasi)</span>
                        <span class="fw-bold text-dark" id="txt_risk_econ">45%</span>
                    </div>
                    <div class="progress"><div class="progress-bar bg-danger" id="bar_risk_econ" style="width: 45%"></div></div>
                </div>

                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold text-muted" style="font-size: 13px;"><i class="fa-regular fa-newspaper text-primary me-2"></i>Sentimen Berita Politik & Logistik</span>
                        <span class="fw-bold text-dark" id="txt_risk_pol">15%</span>
                    </div>
                    <div class="progress"><div class="progress-bar bg-primary" id="bar_risk_pol" style="width: 15%"></div></div>
                </div>
                
                <div class="mt-4 p-3 bg-light rounded-3 border">
                    <p class="mb-0 text-muted" style="font-size: 12px; line-height: 1.5;">
                        <strong>Catatan AI:</strong> <span id="c_ai_note">Risiko ekonomi menjadi faktor dominan di negara ini akibat fluktuasi nilai tukar. Pantau pergerakan kargo maritim secara berkala.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 2: PETA ZOOM NEGARA -->
    <div class="row mt-1 mb-4">
        <div class="col-12">
            <div class="dashboard-panel p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 px-2 pt-2">
                    <h6 class="panel-title mb-0">Local Geography & Ports Focus</h6>
                    <span class="badge bg-light text-dark border px-3 py-1 rounded-pill"><i class="fa-solid fa-satellite text-primary me-1"></i> Satellite / Terrain Mode</span>
                </div>
                <div id="countryMap"></div>
            </div>
        </div>
    </div>

</div>
@endsection

@stack('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Inisialisasi Peta (Default ke Indonesia)
        const map = L.map('countryMap').setView([-0.7893, 113.9213], 5); 
        
        // Kita pakai peta versi agak gelap/detail untuk membedakan dengan overview global
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; CARTO'
        }).addTo(map);

        let currentMarker = L.marker([-0.7893, 113.9213]).addTo(map).bindPopup("<b>Indonesia</b><br>Pusat Pantauan").openPopup();

        // 2. Data Dummy Lengkap per Negara
        const countryData = {
            'ID': { 
                name: 'Indonesia', flag: 'id', region: 'Southeast Asia', capital: 'Jakarta', pop: '275.5 Juta', cur: 'IDR (Rupiah)', gdp: '$ 1.3 Triliun', 
                coords: [-0.7893, 113.9213], zoom: 5, riskTotal: '38%', riskW: 20, riskE: 45, riskP: 15, status: 'Medium Risk', statusClass: 'bg-warning text-dark',
                note: 'Risiko ekonomi menjadi faktor dominan di negara ini akibat inflasi moderat. Jalur pelabuhan beroperasi normal tanpa kendala cuaca ekstrem.'
            },
            'DE': { 
                name: 'Germany', flag: 'de', region: 'Western Europe', capital: 'Berlin', pop: '83.2 Juta', cur: 'EUR (Euro)', gdp: '$ 4.2 Triliun', 
                coords: [51.1657, 10.4515], zoom: 6, riskTotal: '65%', riskW: 10, riskE: 75, riskP: 50, status: 'High Risk', statusClass: 'bg-danger',
                note: 'Terdapat sentimen berita negatif terkait aksi mogok kerja di Pelabuhan Hamburg. Pengiriman kargo mengalami keterlambatan yang signifikan.'
            },
            'US': { 
                name: 'United States', flag: 'us', region: 'North America', capital: 'Washington, D.C.', pop: '333.2 Juta', cur: 'USD (US Dollar)', gdp: '$ 26.9 Triliun', 
                coords: [37.0902, -95.7129], zoom: 4, riskTotal: '42%', riskW: 60, riskE: 30, riskP: 20, status: 'Medium Risk', statusClass: 'bg-warning text-dark',
                note: 'Peringatan cuaca ekstrem (Badai) di pantai timur. Terjadi sedikit inflasi namun daya beli pasar masih sangat kuat.'
            },
            'CN': { 
                name: 'China', flag: 'cn', region: 'East Asia', capital: 'Beijing', pop: '1.4 Miliar', cur: 'CNY (Yuan)', gdp: '$ 17.7 Triliun', 
                coords: [35.8617, 104.1954], zoom: 4, riskTotal: '25%', riskW: 15, riskE: 20, riskP: 30, status: 'Low Risk', statusClass: 'bg-success',
                note: 'Aktivitas pabrik dan ekspor berjalan maksimal. Kebijakan logistik baru mempercepat proses *clearance* di pelabuhan utama.'
            }
        };

        // 3. Logika Perubahan saat Dropdown Dipilih
        document.getElementById('countrySelector').addEventListener('change', function() {
            const data = countryData[this.value];
            
            // Animasi Peta ke Negara Baru
            map.flyTo(data.coords, data.zoom, { duration: 1.5 });
            map.removeLayer(currentMarker);
            currentMarker = L.marker(data.coords).addTo(map).bindPopup(`<b>${data.name}</b><br>Pusat Pantauan`).openPopup();

            // Ubah Teks DOM
            document.getElementById('c_flag').src = `https://flagcdn.com/w80/${data.flag}.png`;
            document.getElementById('c_name').innerText = data.name;
            document.getElementById('c_region').innerHTML = `<i class="fa-solid fa-location-dot me-1"></i> ${data.region}`;
            document.getElementById('c_capital').innerText = data.capital;
            document.getElementById('c_pop').innerText = data.pop;
            document.getElementById('c_cur').innerText = data.cur;
            document.getElementById('c_gdp').innerText = data.gdp;
            
            // Ubah Risk Status Badge
            const statusBadge = document.getElementById('c_status');
            statusBadge.className = `badge px-3 py-2 rounded-pill fw-bold ${data.statusClass}`;
            statusBadge.innerText = data.status;

            // Ubah Progress Bar Risiko
            document.getElementById('c_risk_total').innerText = data.riskTotal;
            
            document.getElementById('txt_risk_weather').innerText = data.riskW + '%';
            document.getElementById('bar_risk_weather').style.width = data.riskW + '%';
            
            document.getElementById('txt_risk_econ').innerText = data.riskE + '%';
            document.getElementById('bar_risk_econ').style.width = data.riskE + '%';
            
            document.getElementById('txt_risk_pol').innerText = data.riskP + '%';
            document.getElementById('bar_risk_pol').style.width = data.riskP + '%';

            // Ubah Catatan AI
            document.getElementById('c_ai_note').innerText = data.note;
        });
    });
</script>