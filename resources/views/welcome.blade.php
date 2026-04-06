<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPEL - Monitoring Sensus Pelanggan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .stat-grid { display: grid !important; grid-template-columns: repeat(2, minmax(0, 1fr)) !important; gap: 0.75rem !important; }
        @media (min-width: 1024px) { .stat-grid { grid-template-columns: repeat(4, minmax(0, 1fr)) !important; gap: 1.5rem !important; } }
        #map { height: 450px; border-radius: 2rem; border: 1px solid #e2e8f0; width: 100%; z-index: 10; }
        .gradient-text { background: linear-gradient(135deg, #2563eb, #1e40af); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .loading-overlay { position: fixed; inset: 0; background: rgba(255,255,255,0.7); display: none; align-items: center; justify-content: center; z-index: 100; backdrop-filter: blur(4px); }
    </style>
</head>
<body class="antialiased">
    <div id="loading" class="loading-overlay">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
    </div>

    <!-- Clean Nav -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 p-2 rounded-lg shadow-lg shadow-blue-600/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-900 border-r pr-4 mr-4 border-slate-200 inline-block">SIMPEL</h1>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden sm:inline-block">Sensus Informasi & Manajemen Pelanggan</span>
                </div>
            </div>
            <a href="/login" class="text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors">LOGIN PETUGAS &rarr;</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div id="live-clock" class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] mb-2 drop-shadow-sm">Memuat waktu...</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight">Monitoring Sensus <span class="gradient-text">Pelanggan</span></h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Analisis capaian and performa pendataan lapangan real-time.</p>
            </div>
            <div class="flex bg-slate-200/50 p-1 rounded-xl w-fit">
                <button onclick="updateFilter('daily')" id="btn-daily" class="px-6 py-2 text-xs font-bold rounded-lg transition-all {{ $currentFilter == 'daily' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500' }}">HARIAN</button>
                <button onclick="updateFilter('monthly')" id="btn-monthly" class="px-6 py-2 text-xs font-bold rounded-lg transition-all {{ $currentFilter == 'monthly' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500' }}">BULANAN</button>
            </div>
        </div>

        <!-- Progress Indicators -->
        <div class="stat-grid mb-8">
             <div class="bg-blue-600 p-5 md:p-6 rounded-3xl text-white shadow-lg overflow-hidden relative">
                <span class="block text-[8px] md:text-[10px] font-bold opacity-60 uppercase tracking-widest mb-1">Capaian Sensus</span>
                <span id="perc-completion" class="text-2xl md:text-3xl font-black block">{{ $percCompletion }}%</span>
                <div class="w-full bg-white/20 h-1.5 rounded-full mt-3"><div class="bg-white h-1.5 rounded-full transition-all duration-1000" style="width: {{ $percCompletion }}%"></div></div>
             </div>
             <div class="bg-white p-5 md:p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pelanggan Aktif</span>
                    <span id="stat-aktif" class="text-xl md:text-2xl font-black text-slate-900">{{ number_format($statAktif) }}</span>
                </div>
                <div class="bg-green-50 p-2 rounded-xl text-green-600"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
             </div>
             <div class="bg-white p-5 md:p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tutup Sem.</span>
                    <span id="stat-tutup-sem" class="text-xl md:text-2xl font-black text-slate-900">{{ number_format($statTutupSem) }}</span>
                </div>
                <div class="bg-amber-50 p-2 rounded-xl text-amber-600"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
             </div>
             <div class="bg-white p-5 md:p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tutup Ttp/Bongkar</span>
                    <span id="stat-tutup-ttp" class="text-xl md:text-2xl font-black text-slate-900">{{ number_format($statTutupTetap + $statBongkar) }}</span>
                </div>
                <div class="bg-red-50 p-2 rounded-xl text-red-600"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
             </div>
        </div>

        <!-- 4-Stat Grid (Survey Status) -->
        <div class="stat-grid mb-8">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm transition-transform hover:-translate-y-1">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Target Sensus</span>
                <span id="stat-target" class="text-2xl font-black text-slate-900 leading-none">{{ number_format($totalTarget) }}</span>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm transition-transform hover:-translate-y-1 border-l-4 border-l-green-500">
                <span class="block text-[10px] font-bold text-green-600 uppercase tracking-widest mb-1">Data Valid</span>
                <span id="stat-valid" class="text-2xl font-black text-green-600 leading-none">{{ number_format($statValid) }}</span>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm transition-transform hover:-translate-y-1 border-l-4 border-l-amber-500">
                <span class="block text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-1">Review/Pending</span>
                <span id="stat-review" class="text-2xl font-black text-slate-900 leading-none">{{ number_format($statReview) }}</span>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm transition-transform hover:-translate-y-1 border-l-4 border-l-blue-500">
                <span class="block text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1">Sisa Survey</span>
                <span id="stat-unsurveyed" class="text-2xl font-black text-slate-900 leading-none">{{ number_format($statBelumSurvey) }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <!-- Trend Card -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                   <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tren Performa</span>
                   <div class="flex items-center space-x-3">
                        <span id="trend-icon" class="p-3 {{ $trendAnalysis['type'] == 'up' ? 'bg-green-50 text-green-600' : ($trendAnalysis['type'] == 'down' ? 'bg-red-50 text-red-600' : 'bg-slate-50 text-slate-400') }} rounded-2xl">
                            @if($trendAnalysis['type'] == 'up') <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> 
                            @elseif($trendAnalysis['type'] == 'down') <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
                            @else <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h14"></path></svg> @endif
                        </span>
                        <div>
                            <span id="trend-value" class="text-2xl font-black {{ $trendAnalysis['type'] == 'up' ? 'text-green-600' : ($trendAnalysis['type'] == 'down' ? 'text-red-600' : 'text-slate-400') }}">{{ $trendAnalysis['value'] }}%</span>
                            <span id="trend-text" class="block text-[10px] font-bold text-slate-400 uppercase">{{ $trendAnalysis['text'] }} ({{ $currentFilter }})</span>
                        </div>
                   </div>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Top Performer</span>
                    <span id="top-surveyor" class="text-sm font-black text-slate-900 block">{{ $topSurveyor }}</span>
                </div>
            </div>

            <!-- Quality Card -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                   <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Rata-rata Kualitas Data</span>
                   <span id="avg-points" class="text-2xl font-black text-blue-600 block">{{ $avgPoints }} Pts</span>
                </div>
                <div class="bg-blue-50 p-4 rounded-3xl text-blue-600 shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-[3rem] border border-slate-200 shadow-sm flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Lokasi Sensus
                    </h3>
                </div>
                <div id="map"></div>
            </div>

            <div class="bg-white p-6 md:p-10 rounded-3xl md:rounded-[3rem] border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-[10px] md:text-sm font-bold uppercase tracking-widest text-slate-400 mb-2">Statistik Akumulasi</h3>
                    <p class="text-xl md:text-2xl font-black text-slate-900 mb-6 md:mb-10 leading-tight">Tren Input <span class="text-blue-600 border-b-4 border-blue-100 pb-1" id="filter-label">DAILY</span></p>
                    <div class="h-[200px] md:h-[250px]">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-slate-200 grid grid-cols-2 gap-6">
                    <div><span class="block text-[8px] font-black text-slate-400 uppercase mb-1">Protection</span><span class="text-xs font-bold text-blue-600">PDI Protected</span></div>
                    <div><span class="block text-[8px] font-black text-slate-400 uppercase mb-1">Version</span><span class="text-xs font-bold text-slate-900">1.0.2-Stable</span></div>
                </div>
            </div>
        </div>
    </main>

    <footer class="max-w-7xl mx-auto px-6 border-t border-slate-200 py-10">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center space-x-3">
                <div class="bg-slate-900 p-1.5 rounded-md">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="text-[10px] font-black text-slate-900 tracking-wider">SIMPEL V1.0.2</span>
            </div>
            <div class="text-center md:text-right">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                    &copy; {{ date('Y') }} SIMPEL MONITORING | INSTANSI / PERUSAHAAN
                </p>
            </div>
        </div>
    </footer>

    <script>
        let trendChart = null;
        let map = null;
        const mapMarkers = L.layerGroup();

        function initMap(data) {
            if (!map) {
                map = L.map('map', { scrollWheelZoom: false, zoomControl: false }).setView([-7.38, 109.35], 11);
                
                const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
                    attribution: '&copy; OpenStreetMap contributors' 
                });
                
                const googleHybrid = L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains:['mt0','mt1','mt2','mt3'],
                    attribution: '&copy; Google Maps'
                });

                googleHybrid.addTo(map); // Default to Satellite/Hybrid
                
                const baseMaps = {
                    "Street View": osm,
                    "Satellite/Hybrid": googleHybrid
                };

                L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);
                L.control.zoom({ position: 'bottomright' }).addTo(map);
                mapMarkers.addTo(map);
            }
            mapMarkers.clearLayers();
            data.forEach(item => {
                const color = item.status === 'valid' ? '#22c55e' : (item.status === 'revisi' ? '#ef4444' : '#f59e0b');
                L.circleMarker([item.lat, item.lng], { radius: 7, fillColor: color, color: '#fff', weight: 2, opacity: 1, fillOpacity: 0.9 }).addTo(mapMarkers);
            });
        }

        function initChart(labels, values) {
            const ctx = document.getElementById('trendChart').getContext('2d');
            if (trendChart) trendChart.destroy();
            trendChart = new Chart(ctx, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Input', data: values, backgroundColor: '#3b82f6', borderRadius: 10 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: 'rgba(0, 0, 0, 0.06)' }, ticks: { color: 'rgba(0, 0, 0, 0.4)', font: { size: 9 } } },
                        x: { grid: { display: false }, ticks: { color: 'rgba(0, 0, 0, 0.4)', font: { size: 9 } } }
                    }
                }
            });
        }

        async function updateFilter(type) {
            document.getElementById('loading').style.display = 'flex';
            try {
                const res = await fetch(`/api/dashboard-stats?filter=${type}`);
                const data = await res.json();
                document.getElementById('btn-daily').className = `px-6 py-2 text-xs font-bold rounded-lg ${type == 'daily' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500'}`;
                document.getElementById('btn-monthly').className = `px-6 py-2 text-xs font-bold rounded-lg ${type == 'monthly' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500'}`;
                
                document.getElementById('stat-target').innerText = data.totalTarget.toLocaleString();
                document.getElementById('stat-valid').innerText = data.statValid.toLocaleString();
                document.getElementById('stat-review').innerText = data.statReview.toLocaleString();
                document.getElementById('stat-unsurveyed').innerText = data.statBelumSurvey.toLocaleString();
                document.getElementById('stat-aktif').innerText = data.statAktif.toLocaleString();
                document.getElementById('stat-tutup-sem').innerText = data.statTutupSem.toLocaleString();
                document.getElementById('stat-tutup-ttp').innerText = (data.statTutupTetap + data.statBongkar).toLocaleString();

                document.getElementById('perc-completion').innerText = data.percCompletion + '%';
                document.getElementById('top-surveyor').innerText = data.topSurveyor;
                document.getElementById('avg-points').innerText = data.avgPoints + ' Pts';
                document.getElementById('filter-label').innerText = type.toUpperCase();

                const trendIcon = document.getElementById('trend-icon');
                const trendVal = document.getElementById('trend-value');
                const trendTxt = document.getElementById('trend-text');
                trendVal.innerText = data.trendAnalysis.value + '%';
                trendVal.className = `text-2xl font-black ${data.trendAnalysis.type == 'up' ? 'text-green-600' : (data.trendAnalysis.type == 'down' ? 'text-red-600' : 'text-slate-400')}`;
                trendTxt.innerText = `${data.trendAnalysis.text} (${type})`;
                trendIcon.className = `p-3 ${data.trendAnalysis.type == 'up' ? 'bg-green-50 text-green-600' : (data.trendAnalysis.type == 'down' ? 'bg-red-50 text-red-600' : 'bg-slate-50 text-slate-400')} rounded-2xl`;
                
                initChart(data.chartLabels, data.chartValues);
                initMap(data.mapData);
            } catch (err) { console.error(err); } finally { document.getElementById('loading').style.display = 'none'; }
        }

        function updateClock() {
            const now = new Date();
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            const day = days[now.getDay()];
            const dd = now.getDate();
            const mm = months[now.getMonth()];
            const yyyy = now.getFullYear();
            const hh = String(now.getHours()).padStart(2, '0');
            const mi = String(now.getMinutes()).padStart(2, '0');
            const ss = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('live-clock').textContent = day + ', ' + dd + ' ' + mm + ' ' + yyyy + ' — ' + hh + ':' + mi + ':' + ss + ' WIB';
        }

        document.addEventListener('DOMContentLoaded', () => {
            initChart(@json($chartLabels), @json($chartValues));
            initMap(@json($mapData));
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
</body>
</html>
