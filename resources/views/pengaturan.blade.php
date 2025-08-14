@extends('app')

@section('content')
<div class="flex h-screen bg-gray-100 overflow-hidden">

    <!-- Mobile overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-30 z-30 hidden md:hidden"
        onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 shadow-md transform -translate-x-full md:translate-x-0 md:relative md:flex transition-transform duration-300 ease-in-out rounded-r-2xl md:rounded-none">
        <div class="p-6 w-full">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/logo.png') }}" class="w-16" alt="Logo">
            </div>
            <h2 class="text-center text-lg font-bold text-gray-800 mb-6">KPP Mining</h2>
            <nav class="divide-y divide-gray-200">
                <a href="/dashboard"
                    class="flex items-center gap-2 px-4 py-3 hover:bg-blue-100 text-gray-700 font-medium transition rounded-md">
                    📊 Dashboard
                </a>
                <a href="/temuan"
                    class="flex items-center gap-2 px-4 py-3 hover:bg-blue-100 text-gray-700 font-medium transition rounded-md">
                    📝 Temuan Harian
                </a>
                <a href="/tindakan"
                    class="flex items-center gap-2 px-4 py-3 hover:bg-blue-100 text-gray-700 font-medium transition rounded-md">
                    ✅ Tindakan Temuan
                </a>
                <a href="/perbaikan"
                    class="flex items-center gap-2 px-4 py-3 hover:bg-blue-100 text-gray-700 font-medium transition rounded-md">
                    🛠 Perbaikan Unit
                </a>
                <a href="/unit"
                    class="flex items-center gap-2 px-4 py-3 hover:bg-blue-100 text-gray-700 font-medium transition rounded-md">
                    🔋 Status Unit
                </a>
                <a href="/tools"
                    class="flex items-center gap-2 px-4 py-3 hover:bg-blue-100 text-gray-700 font-medium transition rounded-md">
                    🧰 Peralatan Pitstop
                </a>
                <a href="/learning"
                    class="flex items-center gap-2 px-4 py-3 hover:bg-blue-100 text-gray-700 font-medium transition rounded-md">
                    📚 Learning Center
                </a>
                <a href="/pengaturan"
                    class="flex items-center gap-2 px-4 py-3 bg-blue-100 text-gray-700 font-medium transition rounded-md">
                    ⚙️ Pengaturan
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col w-screen overflow-auto">


        <!-- Topbar for Mobile -->
        <div class="md:hidden flex items-center justify-between bg-white px-4 py-3 shadow">
            <div class="flex items-center gap-2">
                <img src="{{ asset('img/logo.png') }}" class="h-8" alt="Logo">
                <span class="font-bold text-gray-800">KPP Mining</span>
            </div>
            <button onclick="toggleSidebar()" class="text-gray-700 text-2xl focus:outline-none">☰</button>
        </div>

        <!-- Header -->
        <header class="p-4 sm:p-6 bg-white shadow-md w-full flex justify-between items-center">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">⚙️ Pengaturan Akun</h1>
                <p class="text-sm text-gray-500">Selamat datang, berikut statistik backlog Anda</p>
            </div>

            <!-- Tombol Logout -->
            <div>
                <button id="logoutBtn"
                    class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition">
                    Logout
                </button>
            </div>

            <!-- Modal Logout -->
            <div id="logoutModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800">Konfirmasi Logout</h2>
                    <p class="text-sm text-gray-600">Apakah Anda yakin ingin keluar dari aplikasi?</p>
                    <div class="flex justify-end gap-2">
                        <button id="cancelLogout"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Batal</button>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

        </header>
<main class="p-4 sm:p-6 flex-1 w-full max-w-3xl mx-auto space-y-6">

    <form class="space-y-6 bg-white p-6 rounded-xl border border-gray-200 shadow">

        <!-- Foto Profil -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Foto Profil</label>
            <div class="flex items-center gap-4">
                <img src="/img/engine.jpg" alt="Profile Picture" class="w-16 h-16 rounded-full object-cover border">
                <input type="file" accept="image/*" class="text-sm">
            </div>
        </div>

        <!-- Nama -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
            <input type="text" class="w-full border rounded-md p-2" placeholder="Masukkan nama lengkap">
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
            <input type="email" class="w-full border rounded-md p-2" placeholder="Masukkan email aktif">
        </div>

        <!-- Ganti Password -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Password Baru</label>
            <input type="password" class="w-full border rounded-md p-2" placeholder="Biarkan kosong jika tidak ingin mengubah">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password</label>
            <input type="password" class="w-full border rounded-md p-2" placeholder="Ulangi password baru">
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</main>


        <button id="backToTop" onclick="scrollToTop()" aria-label="Back to Top"
            class="fixed bottom-6 right-6 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-opacity duration-300 opacity-0 invisible z-50">
            ↑
        </button>


    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const backToTopBtn = document.getElementById('backToTop');

    const container = document.querySelector('.flex-1.overflow-auto');

    container.addEventListener('scroll', () => {
        if (container.scrollTop > 200) {
            backToTopBtn.classList.remove('opacity-0', 'invisible');
        } else {
            backToTopBtn.classList.add('opacity-0', 'invisible');
        }
    });


    function scrollToTop() {
        container.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

</script>
<script>
    // Sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

</script>
<script>
    document.getElementById('logoutBtn').addEventListener('click', () => {
        document.getElementById('logoutModal').classList.remove('hidden');
    });

    document.getElementById('cancelLogout').addEventListener('click', () => {
        document.getElementById('logoutModal').classList.add('hidden');
    });

    // Klik luar modal untuk tutup
    document.getElementById('logoutModal').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) {
            e.currentTarget.classList.add('hidden');
        }
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('toggleFormBtn');
        const form = document.getElementById('formTemuan');
        const table = document.getElementById('tabelTemuan');
        const addText = document.getElementById('addText');
        const backText = document.getElementById('backText');

        toggleBtn.addEventListener('click', () => {
            const isHidden = form.classList.contains('hidden');

            form.classList.toggle('hidden');
            table.classList.toggle('hidden');

            addText.classList.toggle('hidden');
            backText.classList.toggle('hidden');
        });

        // Tombol Cancel (jika ada)
        const cancelBtn = document.querySelector('button[type="button"]');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                form.classList.add('hidden');
                table.classList.remove('hidden');
                addText.classList.remove('hidden');
                backText.classList.add('hidden');
            });
        }
    });

</script>


@endsection
