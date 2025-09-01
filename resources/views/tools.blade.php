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
                        class="flex items-center gap-2 px-4 py-3 bg-blue-100 text-gray-700 font-medium transition rounded-md">
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
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">🧰 Tools Pitstop</h1>
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
                            <span id="addText">+ Tambah Tool</span>
                            <span id="backText" class="hidden">← Kembali</span>
                        </button>

                        <!-- Tombol Export -->
                        <a href="{{ route('tool.export') }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                            📥 Export Excel
                        </a>


                    </div>


                    <!-- Form Tambah Tools -->
                    <div id="formTemuan" class="bg-white rounded-xl shadow p-6 hidden">
                        <h3 class="text-md font-bold text-gray-700 mb-4">Tambah Tools Baru</h3>
                        <form action="{{ route('tools.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Upload Foto Tools -->
                            <div class="mb-4">
                                <label class="block text-sm text-gray-600">Foto Tools</label>
                                <input type="file" name="foto" accept="image/*" class="w-full border rounded-md p-2 mt-1">
                            </div>

                            <!-- Code Tools -->
                            <div class="mb-4">
                                <label class="block text-sm text-gray-600">Lokasi</label>
                                <input type="text" name="lokasi" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: Pitstop A001" required>
                            </div>

                            <!-- Nama Tools -->
                            <div class="mb-4">
                                <label class="block text-sm text-gray-600">Nama Tools</label>
                                <input type="text" name="nama_tool" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: 1750 OCA" required>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label class="block text-sm text-gray-600">Status</label>
                                <select name="status" class="w-full border rounded-md p-2 mt-1" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="block text-sm text-gray-600">Description</label>
                                <input type="text" name="deskripsi" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: Berfungsi dengan Baik" required>
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('tools.index') }}"
                                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Cancel</a>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
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
                                        <th class="px-4 py-2 border whitespace-nowrap">Nama Tools</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Lokasi</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Description</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Status</th>
                                        <th class="px-4 py-2 border text-center whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-800">
                                    @foreach($tools as $tool)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 border whitespace-nowrap">
                                                <img src="{{ asset($tool->foto ?? 'img/default.jpg') }}" alt="Tool Photo"
                                                    class="w-14 h-14 object-cover rounded">
                                            </td>
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $tool->nama_tool }}</td>
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $tool->lokasi }}</td>
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $tool->deskripsi }}</td>
                                            <td class="px-4 py-2 border">
                                                @if($tool->status == 'baik')
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold text-black-800 bg-green-200 rounded-full">
                                                        {{ $tool->status }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold text-white bg-red-400 rounded-full">
                                                        {{ $tool->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-2">
                                                    <span onclick="openEditModal({{ $tool->id }})"
                                                        class="inline-flex items-center justify-center px-2 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-md hover:bg-yellow-200 cursor-pointer">
                                                        ✏️ Edit
                                                    </span>

                                                    <form action="{{ route('tools.destroy', $tool->id) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center px-2 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200">
                                                            🗑️ Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Edit Tool Modal -->
                                    <div id="editToolModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                                            <h2 class="text-lg font-bold mb-4">Edit Tool</h2>

                                            <form id="editToolForm" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="tool_id" id="edit_tool_id">

                                                <!-- Foto Tools -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Foto Tools</label>
                                                    <input type="file" name="foto" id="edit_foto" accept="image/*"
                                                        class="w-full border rounded-md p-2 mt-1">
                                                    <img id="edit_previewFoto"
                                                        class="w-24 h-24 object-cover rounded mt-2 hidden">
                                                </div>

                                                <!-- Code Tools -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Code Tools</label>
                                                    <input type="text" name="kode_tool" id="edit_kode_tool"
                                                        class="w-full border rounded-md p-2 mt-1" required>
                                                </div>

                                                <!-- Nama Tools -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Nama Tools</label>
                                                    <input type="text" name="nama_tool" id="edit_nama_tool"
                                                        class="w-full border rounded-md p-2 mt-1" required>
                                                </div>

                                                <!-- Status -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Status</label>
                                                    <select name="status" id="edit_status"
                                                        class="w-full border rounded-md p-2 mt-1" required>
                                                        <option value="Baik">Baik</option>
                                                        <option value="Rusak">Rusak</option>
                                                    </select>
                                                </div>

                                                <!-- Description -->
                                                <div class="mb-4">
                                                    <label class="block text-sm text-gray-600">Description</label>
                                                    <input type="text" name="deskripsi" id="edit_deskripsi"
                                                        class="w-full border rounded-md p-2 mt-1" required>
                                                </div>

                                                <!-- Buttons -->
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
                            <span>Showing 1 to {{ $tools->count() }} of {{ $tools->count() }} entries</span>
                            <div class="space-x-2">
                                <!-- You can implement pagination here if needed -->
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
        function openEditModal(toolId) {
            fetch(`/tools/${toolId}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('editToolForm').action = `/tools/${toolId}`;
                    document.getElementById('edit_tool_id').value = toolId;
                    document.getElementById('edit_kode_tool').value = data.kode_tool;
                    document.getElementById('edit_nama_tool').value = data.nama_tool;
                    document.getElementById('edit_status').value = data.status;
                    document.getElementById('edit_deskripsi').value = data.deskripsi;

                    const preview = document.getElementById('edit_previewFoto');
                    if (data.foto) {
                        preview.src = `/storage/${data.foto}`;
                        preview.classList.remove('hidden');
                    } else {
                        preview.classList.add('hidden');
                    }

                    document.getElementById('editToolModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editToolModal').classList.add('hidden');
        }

        document.getElementById('edit_foto').addEventListener('change', function (e) {
            const preview = document.getElementById('edit_previewFoto');
            const file = e.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        });
    </script>


@endsection