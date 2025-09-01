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
                        class="flex items-center gap-2 px-4 py-3 bg-blue-100 text-gray-700 font-medium transition rounded-md">
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
        </aside>r

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
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">🛠 Perbaikan Unit</h1>
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
                            <span id="addText">+ Tambah Perbaikan</span>
                            <span id="backText" class="hidden">← Kembali</span>
                        </button>


                        <!-- Tombol Export -->
                        <a href="{{ route('perbaikan.export') }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                            📥 Export Excel
                        </a>

                    </div>

                    <!-- Form Tambah Perbaikan -->
                    <div id="formTemuan" class="bg-white rounded-xl shadow p-6 hidden">
                        <h3 class="text-md font-bold text-gray-700 mb-4">Tambah Perbaikan Baru</h3>

                        <form action="{{ route('perbaikan.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf


                            <!-- Backlog -->
                            <div>
                                <label class="block text-sm text-gray-600">Pilih Backlog (Opsional)</label>
                                <select name="id_backlog" class="w-full border rounded-md p-2 mt-1">
                                    <option value="">-- Tidak Ada --</option>
                                    @foreach ($backlogs as $backlog)
                                        <option value="{{ $backlog->id }}">
                                            {{ $backlog->code_number }} -
                                            {{ \Illuminate\Support\Str::limit($backlog->deskripsi, 50) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tanggal Perbaikan -->
                            <div>
                                <label class="block text-sm text-gray-600">Tanggal Perbaikan</label>
                                <input type="date" name="tanggal" class="w-full border rounded-md p-2 mt-1" required>
                            </div>

                            <!-- Kode Unit -->
                            <div>
                                <label class="block text-sm text-gray-600">Kode Unit</label>
                                <input type="text" name="kode_unit" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: EXC-123" required>
                            </div>

                            <!-- HM -->
                            <div>
                                <label class="block text-sm text-gray-600">HM</label>
                                <input type="number" name="hm" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: 12345" required>
                            </div>

                            <!-- Component -->
                            <div>
                                <label class="block text-sm text-gray-600">Component</label>
                                <input type="text" name="component" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: Final Drive" required>
                            </div>

                            <!-- Evidence Temuan -->
                            <div>
                                <label class="block text-sm text-gray-600">Evidence Temuan (Foto)</label>
                                <input type="file" name="evidence_temuan" accept="image/*"
                                    class="w-full border rounded-md p-2 mt-1">
                            </div>

                            <!-- Evidence Perbaikan -->
                            <div>
                                <label class="block text-sm text-gray-600">Evidence Perbaikan (Foto)</label>
                                <input type="file" name="evidence_perbaikan" accept="image/*"
                                    class="w-full border rounded-md p-2 mt-1">
                            </div>

                            <!-- PIC Daily -->
                            <div>
                                <label class="block text-sm text-gray-600">PIC Daily</label>
                                <input type="text" name="pic_daily" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: Siti Aminah" >
                            </div>

                            <!-- GL PIC -->
                            <div>
                                <label class="block text-sm text-gray-600">GL PIC</label>
                                <input type="text" name="gl_pic" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: Budi Santoso" >
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm text-gray-600">Status</label>
                                <select name="status" class="w-full border rounded-md p-2 mt-1">
                                    <option value="Open">Open</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </div>

                            <!-- Nama Pembuat -->
                            <div>
                                <label class="block text-sm text-gray-600">Nama Pembuat</label>
                                <input type="text" name="nama_pembuat" class="w-full border rounded-md p-2 mt-1"
                                    placeholder="Contoh: Admin" required>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm text-gray-600">Deskripsi Perbaikan</label>
                                <textarea name="deskripsi" class="w-full border rounded-md p-2 mt-1" rows="3"
                                    required></textarea>
                            </div>

                            <!-- Tombol -->
                            <div class="flex justify-center gap-2">
                                <button type="button" id="cancelFormBtn"
                                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Cancel</button>
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
                                        <th class="px-4 py-2 border whitespace-nowrap">Nomor Lambung</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Component</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Deskripsi</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Tanggal</th>
                                        <th class="px-4 py-2 border whitespace-nowrap">Status</th>
                                        <th class="px-4 py-2 border text-center whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-800">
                                    @foreach($repairs as $repair)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $repair->kode_unit }}</td>
                                            <td class="px-4 py-2 border">{{ $repair->component }}</td>
                                            <td class="px-4 py-2 border">{{ $repair->deskripsi }}</td>
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $repair->tanggal }}</td>
                                            <td class="px-4 py-2 border">
                                                @if($repair->status === 'open')
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold text-black-800 bg-green-200 rounded-full">
                                                        {{ $repair->status }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">
                                                        {{ $repair->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-2">

                                                    <button type="button"
                                                        class="inline-flex items-center justify-center px-2 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-md hover:bg-yellow-200"
                                                        onclick='openEditModal(@json($repair))'>
                                                        ✏️ Edit
                                                    </button>


                                                    <form action="{{ route('perbaikan.destroy', $repair->id) }}" method="POST"
                                                        onsubmit="return confirm('Hapus perbaikan ini?')">
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
                                    <!-- Modal Edit -->
                                    <div id="editModal"
                                        class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                                        <div
                                            class="bg-white rounded-lg w-full sm:w-3/4 md:w-2/3 lg:w-1/2 max-h-[90vh] overflow-y-auto p-6 shadow-xl">
                                            <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Data Perbaikan</h2>

                                            <form id="editForm" method="POST" enctype="multipart/form-data"
                                                class="space-y-4">
                                                @csrf
                                                @method('PUT')

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <!-- Readonly: ID Backlog -->
                                                    <div>
                                                        <label for="edit_id_backlog"
                                                            class="block text-sm font-medium">Backlog ID</label>
                                                        <input type="text" name="id_backlog" id="edit_id_backlog"
                                                            class="w-full border p-2 rounded bg-gray-100" readonly>
                                                    </div>

                                                    <!-- Readonly: Kode Unit -->
                                                    <div>
                                                        <label for="edit_kode_unit" class="block text-sm font-medium">Kode
                                                            Unit</label>
                                                        <input type="text" name="kode_unit" id="edit_kode_unit"
                                                            class="w-full border p-2 rounded bg-gray-100" readonly>
                                                    </div>

                                                    <!-- Readonly: Tanggal -->
                                                    <div>
                                                        <label for="edit_tanggal"
                                                            class="block text-sm font-medium">Tanggal</label>
                                                        <input type="date" name="tanggal" id="edit_tanggal"
                                                            class="w-full border p-2 rounded bg-gray-100" readonly>
                                                    </div>

                                                    <!-- HM -->
                                                    <div>
                                                        <label for="edit_hm" class="block text-sm font-medium">HM</label>
                                                        <input type="number" name="hm" id="edit_hm"
                                                            class="w-full border p-2 rounded">
                                                    </div>

                                                    <!-- Component -->
                                                    <div>
                                                        <label for="edit_component"
                                                            class="block text-sm font-medium">Component</label>
                                                        <input type="text" name="component" id="edit_component"
                                                            class="w-full border p-2 rounded">
                                                    </div>

                                                    
                                                    <!-- Status (default Open) -->
                                                    <div>
                                                        <label for="edit_status"
                                                            class="block text-sm font-medium">Status</label>
                                                        <select name="status" id="edit_status"
                                                            class="w-full border p-2 rounded" required>
                                                            <option value="Open" selected>Open</option>
                                                            <option value="Closed">Closed</option>
                                                        </select>

                                                    </div>

                                                    <!-- Evidence Temuan (readonly + preview) -->
                                                    <div>
                                                        <label class="block text-sm font-medium">Evidence Temuan</label>

                                                        <!-- Preview image -->
                                                        <img id="edit_previewEvidenceTemuan" src="" alt="Evidence Temuan"
                                                            class="w-32 h-32 object-cover rounded hidden mb-2">

                                                        <!-- Readonly input showing filename/path -->
                                                        <input type="text" name="evidence_temuan" id="edit_evidence_temuan"
                                                            class="w-full border p-2 rounded bg-gray-100" readonly>
                                                    </div>

                                                    <!-- Evidence Perbaikan (uploadable + preview) -->
                                                    <div>
                                                        <label for="edit_evidence_perbaikan"
                                                            class="block text-sm font-medium">Evidence Perbaikan</label>

                                                        <img id="edit_previewEvidencePerbaikan" src=""
                                                            alt="Evidence Perbaikan"
                                                            class="w-32 h-32 object-cover rounded hidden mb-2">

                                                        <input type="file" name="evidence_perbaikan"
                                                            id="edit_evidence_perbaikan" accept="image/*"
                                                            class="w-full border p-2 rounded">
                                                    </div>



                                                    <!-- PIC Daily -->
                                                    <div>
                                                        <label for="edit_pic_daily" class="block text-sm font-medium">PIC
                                                            Daily</label>
                                                        <input type="text" name="pic_daily" id="edit_pic_daily"
                                                            class="w-full border p-2 rounded">
                                                    </div>

                                                    <!-- GL PIC -->
                                                    <div>
                                                        <label for="edit_gl_pic" class="block text-sm font-medium">GL
                                                            PIC</label>
                                                        <input type="text" name="gl_pic" id="edit_gl_pic"
                                                            class="w-full border p-2 rounded">
                                                    </div>

                                                    <!-- PIC -->
                                                    <div>
                                                        <label for="edit_pic" class="block text-sm font-medium">PIC</label>
                                                        <input type="text" name="pic" id="edit_pic"
                                                            class="w-full border p-2 rounded">
                                                    </div>

                                                    <!-- Readonly: Nama Pembuat -->
                                                    <div>
                                                        <label for="edit_nama_pembuat"
                                                            class="block text-sm font-medium">Nama Pembuat</label>
                                                        <input type="text" name="nama_pembuat" id="edit_nama_pembuat"
                                                            class="w-full border p-2 rounded bg-gray-100" readonly>
                                                    </div>
                                                </div>

                                                <!-- Deskripsi -->
                                                <div>
                                                    <label for="edit_deskripsi"
                                                        class="block text-sm font-medium">Deskripsi</label>
                                                    <textarea name="deskripsi" id="edit_deskripsi"
                                                        class="w-full border p-2 rounded resize-y min-h-[100px]"></textarea>
                                                </div>

                                                <div class="flex justify-end mt-6 space-x-2">
                                                    <button type="button" onclick="closeEditModal()"
                                                        class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-md">Cancel</button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>




                                </tbody>
                            </table>
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
        function openEditModal(repair) {
            // Set form action
            document.getElementById('editForm').action = `/perbaikan/${repair.id}`;

            // Fill fields
            document.getElementById('edit_id_backlog').value = repair.id_backlog;
            document.getElementById('edit_kode_unit').value = repair.kode_unit;
            document.getElementById('edit_tanggal').value = repair.tanggal;
            document.getElementById('edit_hm').value = repair.hm;
            document.getElementById('edit_component').value = repair.component;
            document.getElementById('edit_evidence_temuan').value = repair.evidence_temuan;
            document.getElementById('edit_pic_daily').value = repair.pic_daily;
            document.getElementById('edit_gl_pic').value = repair.gl_pic;
            document.getElementById('edit_pic').value = repair.pic;
            document.getElementById('edit_status').value = repair.status;
            document.getElementById('edit_nama_pembuat').value = repair.nama_pembuat;
            document.getElementById('edit_deskripsi').value = repair.deskripsi;

            // ✅ Show preview of Evidence Temuan (readonly)
            const temuanPreview = document.getElementById('edit_previewEvidenceTemuan');
            if (repair.evidence_temuan) {
                // If DB only stores filename, prepend uploads folder
                temuanPreview.src = repair.evidence_temuan.startsWith('uploads/')
                    ? `/${repair.evidence_temuan}`
                    : `/uploads/evidences/${repair.evidence_temuan}`;

                temuanPreview.classList.remove('hidden');
            } else {
                temuanPreview.classList.add('hidden');
            }

            // ✅ Show preview of Evidence Perbaikan
            const perbaikanPreview = document.getElementById('edit_previewEvidencePerbaikan');
            if (repair.evidence_perbaikan) {
                perbaikanPreview.src = repair.evidence_perbaikan.startsWith('uploads/')
                    ? `/${repair.evidence_perbaikan}`
                    : `/uploads/evidences/${repair.evidence_perbaikan}`;

                perbaikanPreview.classList.remove('hidden');
            } else {
                perbaikanPreview.classList.add('hidden');
            }

            // Show the modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // ✅ Live preview when uploading new Evidence Perbaikan
        document.getElementById('edit_evidence_perbaikan').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('edit_previewEvidencePerbaikan');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        });
    </script>







@endsection