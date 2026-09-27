<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Transaksi Harian</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#10b981',
                        danger: '#f43f5e',
                        dark: '#0f172a',
                        light: '#f8fafc'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        .toast-enter { animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .toast-exit { animation: fadeOut 0.3s ease-out forwards; }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        
        .glass-panel { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.8); }
        input, select, button { transition: all 0.3s ease; }
    </style>
</head>
<body class="text-slate-800 antialiased h-screen flex overflow-hidden bg-gradient-to-br from-indigo-50 via-slate-50 to-emerald-50 relative">

    <!-- Background Decorative Blobs -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[30rem] h-[30rem] bg-primary/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-[20%] right-[-10%] w-[30rem] h-[30rem] bg-secondary/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-[30rem] h-[30rem] bg-pink-300/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob" style="animation-delay: 4s;"></div>
    </div>

    <!-- Sidebar -->
    <aside class="w-64 bg-dark/95 backdrop-blur-xl text-white hidden md:flex flex-col h-full shadow-2xl z-20 border-r border-slate-800 relative">
        <div class="p-6 flex items-center gap-3 border-b border-slate-700/50">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-indigo-600 flex items-center justify-center text-xl font-bold shadow-lg shadow-indigo-500/30">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <h1 class="text-xl font-bold tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">Transact<span class="text-primary">Pro</span></h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-primary/20 to-transparent text-primary rounded-xl transition-all font-medium border-l-4 border-primary hover:translate-x-1">
                <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
            </a>
            <a href="#tambah-transaksi" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-400 hover:text-white hover:bg-slate-800/50 hover:translate-x-1">
                <i class="fa-solid fa-plus-circle w-5"></i> Tambah Data
            </a>
            <a href="#riwayat" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-400 hover:text-white hover:bg-slate-800/50 hover:translate-x-1">
                <i class="fa-solid fa-clock-rotate-left w-5"></i> Riwayat
            </a>
        </nav>
        <div class="p-4 border-t border-slate-700/50 text-sm text-slate-500 text-center">
            &copy; 2026 IT Professional
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative z-10">
        <!-- Header Mobile -->
        <header class="md:hidden glass-panel shadow-sm p-4 flex items-center justify-between z-10 sticky top-0">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-indigo-600 flex items-center justify-center text-white font-bold shadow-md">
                    <i class="fa-solid fa-wallet text-sm"></i>
                </div>
                <h1 class="font-bold text-lg bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">TransactPro</h1>
            </div>
            <button class="text-slate-500 hover:text-slate-800 transition-colors"><i class="fa-solid fa-bars text-xl"></i></button>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-8 scroll-smooth">
            <!-- Dashboard Header & Cards -->
            <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-4 animate-fade-in">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Ringkasan Keuangan</h2>
                    <p class="text-slate-500 text-sm mt-1 flex items-center gap-2">
                        <i class="fa-solid fa-sparkles text-primary"></i> Pantau arus kas harian Anda dengan visualisasi modern.
                    </p>
                </div>
                <button onclick="exportToExcel()" class="group relative overflow-hidden bg-gradient-to-r from-secondary to-emerald-500 hover:from-emerald-500 hover:to-emerald-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1 flex items-center gap-2 focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out"></div>
                    <i class="fa-solid fa-file-excel relative z-10"></i> <span class="relative z-10">Ekspor Excel</span>
                </button>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Saldo -->
                <div class="glass-panel p-6 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 relative overflow-hidden group animate-fade-in delay-100 border-t border-l border-white">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-primary/20 to-transparent rounded-full blur-xl group-hover:bg-primary/30 transition-all"></div>
                    <div class="absolute top-4 right-6 p-2 opacity-20 group-hover:scale-125 group-hover:opacity-40 transition-all duration-500">
                        <i class="fa-solid fa-building-columns text-5xl text-primary"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-slate-500 font-medium text-sm mb-2 flex items-center gap-2 uppercase tracking-wider text-xs"><span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span> Total Saldo Bersih</p>
                        <h3 class="text-4xl font-extrabold text-slate-800 tracking-tight" id="card-saldo">Rp 0</h3>
                    </div>
                </div>
                <!-- Pemasukan -->
                <div class="glass-panel p-6 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 relative overflow-hidden group animate-fade-in delay-200 border-t border-l border-white">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-secondary/20 to-transparent rounded-full blur-xl group-hover:bg-secondary/30 transition-all"></div>
                    <div class="absolute top-4 right-6 p-2 opacity-20 group-hover:scale-125 group-hover:opacity-40 transition-all duration-500">
                        <i class="fa-solid fa-arrow-trend-up text-5xl text-secondary"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-slate-500 font-medium text-sm mb-2 flex items-center gap-2 uppercase tracking-wider text-xs"><span class="w-2 h-2 rounded-full bg-secondary"></span> Total Pemasukan</p>
                        <h3 class="text-3xl font-bold text-secondary tracking-tight" id="card-pemasukan">Rp 0</h3>
                    </div>
                </div>
                <!-- Pengeluaran -->
                <div class="glass-panel p-6 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 relative overflow-hidden group animate-fade-in delay-300 border-t border-l border-white">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-danger/20 to-transparent rounded-full blur-xl group-hover:bg-danger/30 transition-all"></div>
                    <div class="absolute top-4 right-6 p-2 opacity-20 group-hover:scale-125 group-hover:opacity-40 transition-all duration-500">
                        <i class="fa-solid fa-arrow-trend-down text-5xl text-danger"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-slate-500 font-medium text-sm mb-2 flex items-center gap-2 uppercase tracking-wider text-xs"><span class="w-2 h-2 rounded-full bg-danger"></span> Total Pengeluaran</p>
                        <h3 class="text-3xl font-bold text-danger tracking-tight" id="card-pengeluaran">Rp 0</h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Entry -->
                <div class="glass-panel rounded-3xl shadow-lg shadow-slate-200/50 p-6 lg:col-span-1 border border-white animate-fade-in delay-400" id="tambah-transaksi">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-primary"><i class="fa-solid fa-plus text-sm"></i></div>
                        Catat Transaksi
                    </h3>
                    <form id="transaction-form" class="space-y-5">
                        <!-- Jenis Transaksi -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Transaksi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" value="income" class="peer sr-only" required>
                                    <div class="text-center py-2.5 px-3 bg-white/50 border-2 border-slate-100 rounded-xl peer-checked:bg-emerald-50 peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary/50 transition-all font-semibold text-sm text-slate-500 shadow-sm group-hover:shadow">
                                        <i class="fa-solid fa-arrow-down mr-1"></i> Pemasukan
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" value="expense" class="peer sr-only" checked>
                                    <div class="text-center py-2.5 px-3 bg-white/50 border-2 border-slate-100 rounded-xl peer-checked:bg-rose-50 peer-checked:border-danger peer-checked:text-danger hover:border-danger/50 transition-all font-semibold text-sm text-slate-500 shadow-sm group-hover:shadow">
                                        <i class="fa-solid fa-arrow-up mr-1"></i> Pengeluaran
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Tanggal & Kategori -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                                <input type="date" id="input-date" required class="w-full bg-white/70 border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all hover:bg-white shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                                <select id="input-category" required class="w-full bg-white/70 border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all hover:bg-white shadow-sm cursor-pointer">
                                    <option value="">Pilih...</option>
                                    <option value="Penjualan">Penjualan</option>
                                    <option value="Bahan Baku">Bahan Baku</option>
                                    <option value="Operasional">Operasional</option>
                                    <option value="Gaji">Gaji</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <!-- Nama Barang / Keterangan -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Barang Bagus / Deskripsi</label>
                            <input type="text" id="input-name" placeholder="Contoh: Kertas HVS A4" required class="w-full bg-white/70 border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all hover:bg-white shadow-sm">
                        </div>

                        <!-- Harga & Kuantitas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Harga Satuan (Rp)</label>
                                <input type="number" id="input-price" min="0" placeholder="0" required class="w-full bg-white/70 border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all hover:bg-white shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah/Qty</label>
                                <input type="number" id="input-qty" min="1" value="1" required class="w-full bg-white/70 border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all hover:bg-white shadow-sm">
                            </div>
                        </div>

                        <button type="submit" class="w-full group relative overflow-hidden bg-gradient-to-r from-primary to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-bold py-3 rounded-xl transition-all mt-4 focus:ring-4 focus:ring-primary/30 shadow-lg shadow-indigo-500/40 hover:-translate-y-1">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i> Simpan Transaksi
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Grafik Visualisasi -->
                <div class="glass-panel rounded-3xl shadow-lg shadow-slate-200/50 p-6 lg:col-span-2 flex flex-col border border-white animate-fade-in delay-400">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-secondary"><i class="fa-solid fa-chart-line text-sm"></i></div>
                            Analisis Arus Kas
                        </h3>
                        <span class="text-xs font-medium px-3 py-1 bg-slate-100 text-slate-500 rounded-full shadow-inner">7 Hari Terakhir</span>
                    </div>
                    <div class="flex-1 relative min-h-[280px] w-full">
                        <canvas id="cashflowChart"></canvas>
                    </div>
                </div>

            </div>

            <!-- Tabel Data -->
            <div class="glass-panel rounded-3xl shadow-lg shadow-slate-200/50 border border-white overflow-hidden animate-fade-in delay-400" id="riwayat">
                <div class="p-6 border-b border-slate-200/60 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-white/40">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-500"><i class="fa-solid fa-table-list text-sm"></i></div>
                        Riwayat Transaksi Harian
                    </h3>
                    
                    <div class="flex gap-2">
                        <div class="relative group">
                            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-hover:text-primary transition-colors"></i>
                            <input type="text" id="search-input" placeholder="Cari barang / kategori..." class="pl-10 pr-4 py-2.5 text-sm bg-white/70 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all hover:bg-white shadow-sm w-full sm:w-72">
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="transaction-table">
                        <thead>
                            <tr class="bg-slate-100/50 text-slate-600 text-xs uppercase tracking-widest border-b border-slate-200">
                                <th class="px-6 py-5 font-bold">Tanggal</th>
                                <th class="px-6 py-5 font-bold">Kategori</th>
                                <th class="px-6 py-5 font-bold">Nama Barang</th>
                                <th class="px-6 py-5 font-bold text-right">Harga</th>
                                <th class="px-6 py-5 font-bold text-center">Qty</th>
                                <th class="px-6 py-5 font-bold text-right">Total</th>
                                <th class="px-6 py-5 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body" class="text-sm divide-y divide-slate-100/80 bg-white/30">
                        </tbody>
                    </table>
                </div>
                <div id="empty-state" class="hidden p-16 text-center text-slate-500">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                        <i class="fa-solid fa-box-open text-4xl text-slate-300"></i>
                    </div>
                    <p class="font-medium text-lg">Belum ada data transaksi.</p>
                    <p class="text-sm mt-1 text-slate-400">Silakan catat transaksi baru Anda di formulir atas.</p>
                </div>
            </div>
            
            <br/><br/>
        </div>
    </main>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3"></div>

    <script>
      let transactions = [];
      let chartInstance = null;

// Fungsi untuk menarik data dari database
async function loadData() {
    try {
        const response = await fetch('get_data.php');
        transactions = await response.json();
        renderApp(); // Render ulang tabel dan grafik setelah data didapat
    } catch (error) {
        console.error("Gagal mengambil data:", error);
        showToast("Gagal terhubung ke database", "error");
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('input-date').valueAsDate = new Date();
    loadData(); // Panggil data dari MySQL saat web pertama kali dibuka

    document.getElementById('transaction-form').addEventListener('submit', handleAddTransaction);
    document.getElementById('search-input').addEventListener('input', renderTable);
});

        function renderApp() {
            updateDashboard();
            renderTable();
            renderChart();
        }

        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        };

        function updateDashboard() {
            let totalIncome = 0;
            let totalExpense = 0;
            transactions.forEach(t => {
                if (t.type === 'income') totalIncome += t.total;
                else totalExpense += t.total;
            });
            const netBalance = totalIncome - totalExpense;

            document.getElementById('card-pemasukan').innerText = formatRupiah(totalIncome);
            document.getElementById('card-pengeluaran').innerText = formatRupiah(totalExpense);
            
            const saldoEl = document.getElementById('card-saldo');
            saldoEl.innerText = formatRupiah(netBalance);
            if(netBalance < 0) {
                saldoEl.classList.remove('text-slate-800');
                saldoEl.classList.add('text-danger');
            } else {
                saldoEl.classList.remove('text-danger');
                saldoEl.classList.add('text-slate-800');
            }
        }

        function renderTable() {
            const tbody = document.getElementById('table-body');
            const emptyState = document.getElementById('empty-state');
            const searchQ = document.getElementById('search-input').value.toLowerCase();
            
            tbody.innerHTML = '';
            let filtered = transactions.sort((a, b) => new Date(b.date) - new Date(a.date));
            if (searchQ) {
                filtered = filtered.filter(t => t.name.toLowerCase().includes(searchQ) || t.category.toLowerCase().includes(searchQ));
            }

            if (filtered.length === 0) {
                emptyState.classList.remove('hidden');
                document.getElementById('transaction-table').classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                document.getElementById('transaction-table').classList.remove('hidden');

                filtered.forEach(t => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-white/80 transition-all duration-200 group hover:shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] hover:-translate-y-[1px]';
                    const badgeClass = t.type === 'income' ? 'bg-emerald-100/80 text-emerald-700 border-emerald-200 shadow-sm' : 'bg-rose-100/80 text-rose-700 border-rose-200 shadow-sm';
                    const icon = t.type === 'income' ? '<i class="fa-solid fa-arrow-down mr-1"></i>' : '<i class="fa-solid fa-arrow-up mr-1"></i>';
                    const amountClass = t.type === 'income' ? 'text-emerald-600' : 'text-rose-600';
                    const dateObj = new Date(t.date);
                    const formattedDate = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

                    tr.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 font-medium">${formattedDate}</td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-3 py-1.5 text-xs rounded-lg border font-bold tracking-wide ${badgeClass}">${t.category}</span></td>
                        <td class="px-6 py-4 text-slate-800 font-bold">${t.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-slate-600 font-medium">${formatRupiah(t.price)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-slate-600 font-medium"><span class="bg-slate-100 px-2 py-1 rounded-md">${t.qty}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-extrabold ${amountClass} text-base">${icon} ${formatRupiah(t.total)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button onclick="deleteTransaction(${t.id})" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:bg-rose-100 hover:text-danger transition-all duration-300 hover:rotate-12 focus:outline-none" title="Hapus Data">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        }

        // --- MENYIMPAN DATA KE DATABASE ---
       async function handleAddTransaction(e) {
    e.preventDefault();

    const type = document.querySelector('input[name="type"]:checked').value;
    const date = document.getElementById('input-date').value;
    const category = document.getElementById('input-category').value;
    const name = document.getElementById('input-name').value;
    const price = parseFloat(document.getElementById('input-price').value);
    const qty = parseInt(document.getElementById('input-qty').value);
    
    if (!date || !category || !name || isNaN(price) || isNaN(qty)) {
        showToast("Mohon lengkapi semua field dengan benar.", "error");
        return;
    }

    const total = price * qty;
    const newTx = { date, type, category, name, price, qty, total };

    try {
        // Kirim data ke PHP menggunakan Fetch API
        const response = await fetch('simpan_data.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newTx)
        });
        
        const result = await response.json();

        if (result.status === 'success') {
            showToast("Transaksi berhasil disimpan!", "success");
            
            // Reset Form
            document.getElementById('input-name').value = '';
            document.getElementById('input-price').value = '';
            document.getElementById('input-qty').value = '1';
            
            // Refresh data dari database agar tabel langsung terupdate
            loadData(); 
        } else {
            showToast("Gagal menyimpan transaksi.", "error");
        }
    } catch (error) {
        console.error("Error:", error);
        showToast("Terjadi kesalahan sistem.", "error");
    }
}

        // --- MENGHAPUS DATA DARI DATABASE ---
        async function deleteTransaction(id) {
            try {
                const response = await fetch('hapus_data.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                const result = await response.json();
                if (result.status === 'success') {
                    showToast("Transaksi dihapus.", "success");
                    loadData(); // Tarik data terbaru dari DB
                }
            } catch (error) {
                showToast("Gagal menghapus.", "error");
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-white' : 'bg-red-50';
            const iconColor = type === 'success' ? 'text-secondary' : 'text-danger';
            const iconType = type === 'success' ? 'fa-check-circle' : 'fa-circle-exclamation';
            const border = type === 'success' ? 'border-l-4 border-secondary' : 'border-l-4 border-danger';

            toast.className = `toast-enter ${bgColor} ${border} shadow-lg rounded-r-lg px-4 py-3 flex items-center gap-3 min-w-[300px] border border-y-slate-100 border-r-slate-100`;
            toast.innerHTML = `<i class="fa-solid ${iconType} ${iconColor} text-xl"></i><p class="text-sm font-medium text-slate-700 flex-1">${message}</p><button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>`;
            container.appendChild(toast);
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.replace('toast-enter', 'toast-exit');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 3500);
        }

        function renderChart() {
            const ctx = document.getElementById('cashflowChart').getContext('2d');
            const labels = [];
            const dataIncome = [];
            const dataExpense = [];
            
            for(let i=6; i>=0; i--) {
                const d = new Date(); d.setDate(d.getDate() - i);
                const dateStr = d.toISOString().split('T')[0];
                const displayStr = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                labels.push(displayStr);

                let inc = 0; let exp = 0;
                transactions.forEach(t => {
                    if(t.date === dateStr) {
                        if(t.type === 'income') inc += t.total;
                        else exp += t.total;
                    }
                });
                dataIncome.push(inc); dataExpense.push(exp);
            }

            if (chartInstance) { chartInstance.destroy(); }
            const gradientIncome = ctx.createLinearGradient(0, 0, 0, 400);
            gradientIncome.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
            gradientIncome.addColorStop(1, 'rgba(16, 185, 129, 0.2)');
            const gradientExpense = ctx.createLinearGradient(0, 0, 0, 400);
            gradientExpense.addColorStop(0, 'rgba(244, 63, 94, 0.8)');
            gradientExpense.addColorStop(1, 'rgba(244, 63, 94, 0.2)');

            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Pemasukan (Rp)', data: dataIncome, backgroundColor: gradientIncome, borderColor: '#10b981', borderWidth: 1, borderRadius: 6, barPercentage: 0.5, categoryPercentage: 0.8 },
                        { label: 'Pengeluaran (Rp)', data: dataExpense, backgroundColor: gradientExpense, borderColor: '#f43f5e', borderWidth: 1, borderRadius: 6, barPercentage: 0.5, categoryPercentage: 0.8 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    animation: { y: { duration: 1500, easing: 'easeOutElastic' } },
                    plugins: {
                        legend: { position: 'top', align: 'end', labels: { boxWidth: 12, usePointStyle: true, font: { family: 'Inter', weight: '500' } } },
                        tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.9)', titleFont: { family: 'Inter', size: 13 }, bodyFont: { family: 'Inter', size: 13 }, padding: 12, cornerRadius: 8, displayColors: true }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#e2e8f0' }, ticks: { callback: function(value) { if(value === 0) return 0; return 'Rp ' + (value / 1000) + 'k'; }, font: { family: 'Inter', size: 11, color: '#64748b' } }, border: { display: false } },
                        x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 12, color: '#64748b' } }, border: { display: false } }
                    }
                }
            });
        }

        function exportToExcel() {
            if (transactions.length === 0) { showToast("Tidak ada data untuk diekspor", "error"); return; }
            showToast("Sedang menyiapkan file Excel...", "success");

            const excelData = transactions.map((t, index) => ({
                "No": index + 1, "Tanggal": t.date, "Jenis Transaksi": t.type === 'income' ? 'Pemasukan' : 'Pengeluaran', "Kategori": t.category, "Nama Barang / Deskripsi": t.name, "Kuantitas": t.qty, "Harga Satuan (Rp)": t.price, "Total (Rp)": t.total
            }));

            const worksheet = XLSX.utils.json_to_sheet(excelData);
            const wscols = [ {wch: 5}, {wch: 15}, {wch: 15}, {wch: 15}, {wch: 35}, {wch: 10}, {wch: 20}, {wch: 20} ];
            worksheet['!cols'] = wscols;
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Riwayat Transaksi");

            const dateStr = new Date().toISOString().split('T')[0];
            const fileName = `Laporan_Transaksi_${dateStr}.xlsx`;
            XLSX.writeFile(workbook, fileName);
            setTimeout(() => { showToast("Berhasil diunduh! Silakan cek folder Download Anda.", "success"); }, 1000);
        }
    </script>
</body>
</html>