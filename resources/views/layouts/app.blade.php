<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Supply Chain</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            background-color: #f8fafc; 
            font-family: 'Inter', sans-serif; 
            color: #334155;
            margin: 0;
            overflow: hidden; 
        }
        
        .app-container {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        /* HEADER SERAGAM */
        .sidebar-header, .top-navbar {
            height: 70px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            background-color: #ffffff;
            flex-shrink: 0;
        }

        .sidebar-header { padding: 0 24px; }
        .top-navbar { 
            padding: 0 32px; 
            justify-content: space-between; 
            position: sticky; 
            top: 0; 
            z-index: 50; 
        }

        .header-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.2px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* --- 1. SIDEBAR (KIRI) --- */
        .sidebar {
            width: 260px; 
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 100;
        }
        
        .sidebar-menu {
            padding: 16px 12px;
            overflow-y: auto;
            flex-grow: 1;
        }
        
        .menu-title {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 24px 0 8px 12px;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 14px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border-radius: 8px;
            margin-bottom: 2px;
            transition: all 0.2s;
        }
        
        .menu-item:hover { 
            color: #0f172a; 
            background-color: #f1f5f9; 
        }
        
        /* State Menu Aktif */
        .menu-item.active {
            color: #2563eb;
            background-color: #eff6ff;
            font-weight: 600;
        }
        
        .menu-item i { 
            width: 28px; 
            font-size: 15px;
        }

        /* --- 2. MAIN AREA (TENGAH - BISA DI-SCROLL) --- */
        .main-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow-y: auto; 
            background-color: #f8fafc;
        }

        .content-area {
            flex-grow: 1;
            padding: 32px; 
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body>

    <div class="app-container">
        
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="header-title">
                    <i class="fa-solid fa-cube text-primary" style="font-size: 20px;"></i>
                    Global Supply Chain
                </div>
            </div>
            
            <div class="sidebar-menu">
                <div class="menu-title">Main Dashboard</div>
                
                <!-- Menggunakan Request::is() dari Laravel untuk cek halaman aktif -->
                <a href="/" class="menu-item {{ Request::is('/') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group {{ Request::is('/') ? 'text-primary' : '' }}"></i> Dashboard
                </a>
                
                <a href="/countries" class="menu-item {{ Request::is('countries') ? 'active' : '' }}">
                    <i class="fa-solid fa-map-location-dot {{ Request::is('countries') ? 'text-primary' : '' }}"></i> Countries
                </a>
                
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-chart-line"></i> Analytics Dashboard
                </a>
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-code-compare"></i> Compare Countries
                </a>

                <div class="menu-title">Risk & Monitoring</div>
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-shield-halved"></i> Risk Analysis
                </a>
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-cloud-sun-rain"></i> Weather Monitoring
                </a>
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-ship"></i> Port Dashboard
                </a>

                <div class="menu-title">Market Intelligence</div>
                <a href="#" class="menu-item">
                    <i class="fa-regular fa-newspaper"></i> News Intelligence
                </a>
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-money-bill-trend-up"></i> Currency Dashboard
                </a>
                
                <div class="menu-title">System & Preferences</div>
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-bookmark"></i> Watchlist
                </a>
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-user-shield"></i> Admin
                </a>
            </div>
        </aside>

        <!-- MAIN AREA -->
        <main class="main-area">
            <header class="top-navbar shadow-sm">
                <!-- Kita buat judul atasnya juga dinamis jika mau (opsional) -->
                <div class="header-title text-muted">
                    {{ Request::is('countries') ? 'Country Deep Dive' : 'Overview Dashboard' }}
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">System Online</span>
                </div>
            </header>
            
            <div class="content-area">
                @yield('content')
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>