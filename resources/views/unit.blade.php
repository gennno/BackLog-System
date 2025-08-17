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
                        class="flex items-center gap-2 px-4 py-3 bg-blue-100 text-gray-700 font-medium transition rounded-md">
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
                        class="flex items-center gap-2 px-4 py-3 hover:bg-blue-100 text-gray-700 font-medium transition rounded-md">
                        ⚙️ Pengaturan
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <a href="/pengguna"
                            class="flex items-center gap-2 px-4 py-3 hover:bg-blue-100 text-gray-700 font-medium transition rounded-md">
                            👤 Daftar Pengguna
                        </a>
                    @endif
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
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">🔋 Status Unit</h1>
                    <p class="text-sm text-gray-500">
                        Hallo, {{ Auth::user()->name }}
                    </p>
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


            <!-- Main Section -->
            <main class="p-4 sm:p-6 flex-1 w-full space-y-6">
                <div id="temuanContainer" class="space-y-6">


                    <!-- Top Bar -->
                    <div class="flex justify-between items-center">
                        <!-- Tombol Kiri -->
                        <button id="toggleFormBtn"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                            <span id="addText">+ Tambah Unit</span>
                            <span id="backText" class="hidden">← Kembali</span>
                        </button>

                    </div>

                    <!-- Form Tambah Temuan -->
                    <div id="formTemuan" class="bg-white rounded-xl shadow p-6 hidden">
                        <h3 class="text-md font-bold text-gray-700 mb-4">Tambah Unit Baru</h3>
                        <form id="unitForm" method="POST" action="{{ route('unit.store') }}" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf
                            <!-- Foto Unit -->
                            <div>
                                <label class="block text-sm text-gray-600">Foto Unit</label>
                                <input type="file" name="foto" accept="image/*" class="w-full border rounded-md p-2 mt-1">
                                <img id="previewFoto" class="w-24 h-24 object-cover rounded mt-2 hidden">
                            </div>

                            <!-- Kode Unit -->
                            <div>
                                <label class="block text-sm text-gray-600">Kode Unit</label>
                                <input type="text" name="kode_unit" id="kode_unit" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: DT1234" required>
                            </div>

                            <!-- Nama Unit -->
                            <div>
                                <label class="block text-sm text-gray-600">Nama Unit</label>
                                <input type="text" name="nama_unit" id="nama_unit" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: Excavator Hitachi" required>
                            </div>

                            <!-- Kapasitas Baterai -->
                            <div>
                                <label class="block text-sm text-gray-600">Kapasitas Baterai</label>
                                <input type="text" name="baterai" id="baterai" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: 1750 OCA" required>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm text-gray-600">Status</label>
                                <select name="status" id="status" class="w-full border rounded-md p-2 mt-1" required>
                                    <option value="">Pilih Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="off">Off</option>
                                </select>
                            </div>

                            <!-- Tombol -->
                            <div class="flex justify-center gap-2">
                                <button type="button" onclick="closeAddForm()"
                                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Simpan
                                </button>
                            </div>
                        </form>




                    </div>

                    <!-- Table Section -->
                    <div id="tabelTemuan" class="bg-white rounded-xl shadow p-6">

                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <label class="text-sm text-gray-600">Show</label>
                                <select class="border rounded px-2 py-1 text-sm">
                                    <option>10</option>
                                    <option selected>25</option>
                                    <option>50</option>
                                </select>
                                <span class="text-sm text-gray-600 ml-1">entries</span>
                            </div>
                            <input type="text" placeholder="🔍 Search..."
                                class="border rounded px-3 py-1 text-sm w-48 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full text-sm text-left border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-700">
                                        <th class="px-4 py-2 border whitespace-nowrap">Foto</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Code Unit</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Battery Health</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Status</th>
                                        <th class="px-4 py-2 border text-center whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-800">
                                    @foreach($units as $unit)
                                        <tr id="unitRow-{{ $unit->id }}" class="hover:bg-gray-50">
                                            <td class="px-4 py-2 border whitespace-nowrap">
                                                @if($unit->foto)
                                                    <img src="{{ asset('storage/' . $unit->foto) }}"
                                                        class="w-14 h-14 object-cover rounded">
                                                @else
                                                    <span class="text-gray-500">No Photo</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $unit->kode_unit }}</td>
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $unit->baterai }}</td>
                                            <td class="px-4 py-2 border">
                                                <span
                                                    class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                                                                        {{ $unit->status == 'aktif' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                                    {{ ucfirst($unit->status) }}
                                                </span>
                                            </td>
                                            <!-- Table Row -->
                                            <td class="px-4 py-2 border text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-2">
                                                    <!-- Edit Button -->
                                                    <button type="button" onclick="openEditModal({{ $unit->id }})"
                                                        class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200">
                                                        ✏️ Edit
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <form action="{{ route('unit.destroy', $unit->id) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-2 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                                            🗑️ Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach


                                    <!-- Edit Unit Modal -->
                                    <div id="editUnitModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                                            <h2 class="text-lg font-bold mb-4">Edit Unit</h2>
                                            <form id="editUnitForm" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="unit_id" id="edit_unit_id">

                                                <!-- Foto Unit -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Foto Unit</label>
                                                    <input type="file" name="foto" id="edit_foto" accept="image/*"
                                                        class="w-full border rounded-md p-2 mt-1">
                                                    <img id="edit_previewFoto"
                                                        class="w-24 h-24 object-cover rounded mt-2 hidden">
                                                </div>

                                                <!-- Kode Unit -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Kode Unit</label>
                                                    <input type="text" name="kode_unit" id="edit_kode_unit"
                                                        class="w-full border rounded-md p-2 mt-1" required>
                                                </div>

                                                <!-- Nama Unit -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Nama Unit</label>
                                                    <input type="text" name="nama_unit" id="edit_nama_unit"
                                                        class="w-full border rounded-md p-2 mt-1" required>
                                                </div>

                                                <!-- Baterai -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Kapasitas Baterai</label>
                                                    <input type="text" name="baterai" id="edit_baterai"
                                                        class="w-full border rounded-md p-2 mt-1" required>
                                                </div>

                                                <!-- Status -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Status</label>
                                                    <select name="status" id="edit_status"
                                                        class="w-full border rounded-md p-2 mt-1" required>
                                                        <option value="aktif">Aktif</option>
                                                        <option value="off">Off</option>
                                                    </select>
                                                </div>

                                                <div class="flex justify-end gap-2">
                                                    <button type="button" onclick="closeEditModal()"
                                                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Cancel</button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                </tbody>

                            </table>


                        </div>
                        <div class="mt-4 flex justify-between text-sm text-gray-600">
                            <span>Showing 1 to 1 of 1 entries</span>
                            <div class="space-x-2">
                                <button class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">Prev</button>
                                <button class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">1</button>
                                <button class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">Next</button>
                            </div>
                        </div>
                    </div>

                </div>

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
<script>
document.addEventListener('DOMContentLoaded', function () {

    window.openEditModal = function (unitId) {
        fetch(`/unit/${unitId}/edit`)
            .then(res => res.json())
            .then(data => {
                const form = document.getElementById('editUnitForm');
                form.action = `/unit/${unitId}`;
                document.getElementById('edit_unit_id').value = unitId;
                document.getElementById('edit_kode_unit').value = data.kode_unit;
                document.getElementById('edit_nama_unit').value = data.nama_unit;
                document.getElementById('edit_baterai').value = data.baterai;
                document.getElementById('edit_status').value = data.status;

                const preview = document.getElementById('edit_previewFoto');
                if (data.foto) {
                    preview.src = `/storage/${data.foto}`;
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }

                document.getElementById('editUnitModal').classList.remove('hidden');
            })
            .catch(err => console.error(err));
    }

    window.closeEditModal = function () {
        document.getElementById('editUnitModal').classList.add('hidden');
    }

    // Preview uploaded image
    document.getElementById('edit_foto').addEventListener('change', function (e) {
        const preview = document.getElementById('edit_previewFoto');
        const file = e.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    });

    // AJAX form submission
    document.getElementById('editUnitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST', // Laravel requires POST + _method=PUT
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                alert('Unit updated successfully!');
                closeEditModal();
                location.reload(); // refresh table or page to see changes
            } else {
                alert('Update failed!');
            }
        })
        .catch(err => console.error(err));
    });

});
</script>

@endsection