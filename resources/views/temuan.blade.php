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
                        class="flex items-center gap-2 px-4 py-3 bg-blue-100 text-gray-700 font-medium transition rounded-md">
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
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">📝 Temuan Harian</h1>
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
                            <span id="addText">+ Tambah Temuan</span>
                            <span id="backText" class="hidden">← Kembali</span>
                        </button>

                    </div>

                    <!-- Form Tambah Temuan -->
                    <div id="formTemuan" class="bg-white rounded-xl shadow p-6 hidden">
                        <h3 class="text-md font-bold text-gray-700 mb-4">Tambah Temuan Baru</h3>
                        <form action="{{ route('temuan.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf

                            <!-- Tanggal Temuan -->
                            <div>
                                <label class="block text-sm text-gray-600">Tanggal Temuan</label>
                                <input type="date" name="tanggal_temuan" class="w-full border rounded-md p-2 mt-1" required>
                            </div>

                            <!-- ID Inspeksi -->
                            <div>
                                <label class="block text-sm text-gray-600">ID Inspeksi</label>
                                <input type="text" name="id_inspeksi" placeholder="Contoh: INSP-2025-001"
                                    class="w-full border rounded-md p-2 mt-1" required>
                            </div>

                            <!-- Code Number -->
                            <div>
                                <label class="block text-sm text-gray-600">Nomor Lambung</label>
                                <input type="text" name="code_number" placeholder="Contoh: CN-001"
                                    class="w-full border rounded-md p-2 mt-1">
                            </div>

                            <!-- HM -->
                            <div>
                                <label class="block text-sm text-gray-600">HM</label>
                                <input type="number" name="hm" placeholder="Contoh: 12345"
                                    class="w-full border rounded-md p-2 mt-1">
                            </div>

                            <!-- Component -->
                            <div>
                                <label class="block text-sm text-gray-600">Component</label>
                                <input type="text" name="component" placeholder="Contoh: Final Drive"
                                    class="w-full border rounded-md p-2 mt-1">
                            </div>

                            <!-- Plan Repair -->
                            <div>
                                <label class="block text-sm text-gray-600">Plan Repair</label>
                                <select name="plan_repair" class="w-full border rounded-md p-2 mt-1">
                                    <option value="Next PS">Next PS</option>
                                    <option value="No Repair">No Repair</option>
                                </select>

                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm text-gray-600">Status</label>
                                <select name="status" class="w-full border rounded-md p-2 mt-1">
                                    <option value="Open BL">Open BL</option>
                                    <option value="Close">Close</option>
                                </select>
                            </div>

                            <!-- Condition -->
                            <div>
                                <label class="block text-sm text-gray-600">Condition</label>
                                <select name="condition" class="w-full border rounded-md p-2 mt-1">
                                    <option value="Caution">Caution</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>

                            <!-- GL PIC -->
                            <div>
                                <label class="block text-sm text-gray-600">GL PIC</label>
                                <input type="text" name="gl_pic" placeholder="Contoh: Budi Santoso"
                                    class="w-full border rounded-md p-2 mt-1">

                            </div>

                            <!-- PIC Daily -->
                            <div>
                                <label class="block text-sm text-gray-600">PIC Daily</label>
                                <input type="text" name="pic_daily" placeholder="Contoh: Siti Aminah"
                                    class="w-full border rounded-md p-2 mt-1">

                            </div>

                            <!-- Evidence -->
                            <div>
                                <label class="block text-sm text-gray-600">Evidence (Upload Foto)</label>
                                <input type="file" name="evidence" accept="image/*"
                                    class="w-full border rounded-md p-2 mt-1">
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm text-gray-600">Deskripsi Temuan</label>
                                <textarea name="deskripsi" class="w-full border rounded-md p-2 mt-1" rows="3"></textarea>

                            </div>

                            <!-- Tombol -->
                            <div class="flex justify-center gap-2">
                                <button type="button" @click="showForm = false"
                                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                            </div>

                        </form>

                    </div>

                    <!-- Table Section -->
                    <div id="tabelTemuan" class="bg-white rounded-xl shadow p-6">

                        <!-- Controls -->
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <label class="text-sm text-gray-600">Show</label>
                                <select id="entriesPerPage" class="border rounded px-2 py-1 text-sm">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                </select>
                                <span class="text-sm text-gray-600 ml-1">entries</span>
                            </div>
                            <input type="text" id="tableSearch" placeholder="🔍 Search..."
                                class="border rounded px-3 py-1 text-sm w-48 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table id="dataTable" class="table-auto w-full text-sm text-left border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-700">
                                        <th class="px-4 py-2 border whitespace-nowrap cursor-pointer">Nomor Lambung ⬍</th>
                                        <th class="px-4 py-2 border whitespace-nowrap cursor-pointer">Component ⬍</th>
                                        <th class="px-4 py-2 border cursor-pointer">Deskripsi ⬍</th>
                                        <th class="px-4 py-2 border whitespace-nowrap cursor-pointer">Tanggal ⬍</th>
                                        <th class="px-4 py-2 border whitespace-nowrap cursor-pointer">Condition ⬍</th>
                                        <th class="px-4 py-2 border whitespace-nowrap cursor-pointer">Status ⬍</th>
                                        <th class="px-4 py-2 border text-center whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody" class="text-gray-800">
                                    @foreach($temuan as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $item->code_number }}</td>
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $item->component }}</td>
                                            <td class="px-4 py-2 border">{{ $item->deskripsi }}</td>
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $item->tanggal_temuan }}</td>
                                            <td class="px-4 py-2 border">
                                                @if($item->condition === 'Urgent')
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">
                                                        {{ $item->condition }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">
                                                        {{ $item->condition }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border whitespace-nowrap">{{ $item->status }}</td>
                                            <td class="px-4 py-2 border text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button type="button"
                                                        class="inline-flex items-center justify-center px-2 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-md hover:bg-yellow-200"
                                                        onclick="editTemuan({{ $item->id }}, '{{ $item->tanggal_temuan }}', '{{ $item->code_number }}', '{{ $item->component }}', '{{ $item->status }}', '{{ $item->condition }}', `{{ $item->deskripsi }}`)">
                                                        ✏️ Edit
                                                    </button>

                                                    <form action="{{ route('temuan.destroy', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Yakin hapus temuan ini?')">
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
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 flex justify-between text-sm text-gray-600">
                            <span id="tableInfo">Showing 1 to X of Y entries</span>
                            <div id="paginationControls" class="space-x-2"></div>
                        </div>
                    </div>

                    <!-- Edit Temuan Modal (keep only one, outside loop) -->
                    <div id="editModal"
                        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                            <h3 class="text-lg font-semibold mb-4">Edit Temuan</h3>
                            <form id="editForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-600">Tanggal Temuan</label>
                                        <input type="date" name="tanggal_temuan" id="edit_tanggal_temuan"
                                            class="w-full border rounded-md p-2 mt-1" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Nomor Lambung</label>
                                        <input type="text" name="code_number" id="edit_code_number"
                                            class="w-full border rounded-md p-2 mt-1" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Component</label>
                                        <input type="text" name="component" id="edit_component"
                                            class="w-full border rounded-md p-2 mt-1" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Status</label>
                                        <select name="status" id="edit_status" class="w-full border rounded-md p-2 mt-1"
                                            required>
                                            <option value="Open BL">Open BL</option>
                                            <option value="Close">Close</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Condition</label>
                                        <select name="condition" id="edit_condition"
                                            class="w-full border rounded-md p-2 mt-1">
                                            <option value="Caution">Caution</option>
                                            <option value="Urgent">Urgent</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Deskripsi</label>
                                        <textarea name="deskripsi" id="edit_deskripsi"
                                            class="w-full border rounded-md p-2 mt-1" rows="3"></textarea>
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick="closeEditModal()"
                                            class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Batal</button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                                    </div>
                                </div>
                            </form>
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
        function editTemuan(id, tanggal, code, component, status, condition, deskripsi) {
            // Fill form values
            document.getElementById('edit_tanggal_temuan').value = tanggal;
            document.getElementById('edit_code_number').value = code;
            document.getElementById('edit_component').value = component;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_condition').value = condition;
            document.getElementById('edit_deskripsi').value = deskripsi;

            // Set form action URL
            document.getElementById('editForm').action = "{{ url('temuan') }}/" + id;

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }
    </script>
    <!-- Table Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableBody = document.getElementById("tableBody");
            const searchInput = document.getElementById("tableSearch");
            const entriesSelect = document.getElementById("entriesPerPage");
            const tableInfo = document.getElementById("tableInfo");
            const paginationControls = document.getElementById("paginationControls");
            const headers = document.querySelectorAll("thead th");

            let rows = Array.from(tableBody.querySelectorAll("tr"));
            let currentPage = 1;
            let entriesPerPage = parseInt(entriesSelect.value);
            let sortColumn = null;
            let sortAsc = true;

            function renderTable() {
                const search = searchInput.value.toLowerCase();
                let filtered = rows.filter(row =>
                    row.innerText.toLowerCase().includes(search)
                );

                if (sortColumn !== null) {
                    filtered.sort((a, b) => {
                        const aText = a.cells[sortColumn].innerText.trim();
                        const bText = b.cells[sortColumn].innerText.trim();
                        return sortAsc ? aText.localeCompare(bText) : bText.localeCompare(aText);
                    });
                }

                const total = filtered.length;
                const totalPages = Math.ceil(total / entriesPerPage);
                currentPage = Math.min(currentPage, totalPages) || 1;

                const start = (currentPage - 1) * entriesPerPage;
                const end = start + entriesPerPage;
                const paginated = filtered.slice(start, end);

                tableBody.innerHTML = "";
                paginated.forEach(r => tableBody.appendChild(r));

                tableInfo.textContent = `Showing ${start + 1} to ${Math.min(end, total)} of ${total} entries`;

                paginationControls.innerHTML = "";
                if (totalPages > 1) {
                    if (currentPage > 1) {
                        const prevBtn = document.createElement("button");
                        prevBtn.textContent = "Prev";
                        prevBtn.className = "px-2 py-1 bg-gray-200 rounded hover:bg-gray-300";
                        prevBtn.onclick = () => { currentPage--; renderTable(); };
                        paginationControls.appendChild(prevBtn);
                    }

                    for (let i = 1; i <= totalPages; i++) {
                        const btn = document.createElement("button");
                        btn.textContent = i;
                        btn.className = `px-2 py-1 rounded ${i === currentPage ? "bg-blue-500 text-white" : "bg-gray-200 hover:bg-gray-300"}`;
                        btn.onclick = () => { currentPage = i; renderTable(); };
                        paginationControls.appendChild(btn);
                    }

                    if (currentPage < totalPages) {
                        const nextBtn = document.createElement("button");
                        nextBtn.textContent = "Next";
                        nextBtn.className = "px-2 py-1 bg-gray-200 rounded hover:bg-gray-300";
                        nextBtn.onclick = () => { currentPage++; renderTable(); };
                        paginationControls.appendChild(nextBtn);
                    }
                }
            }

            searchInput.addEventListener("input", () => { currentPage = 1; renderTable(); });
            entriesSelect.addEventListener("change", () => { entriesPerPage = parseInt(entriesSelect.value); currentPage = 1; renderTable(); });

            headers.forEach((header, index) => {
                if (index < headers.length - 1) { // skip "Aksi" column
                    header.addEventListener("click", () => {
                        if (sortColumn === index) {
                            sortAsc = !sortAsc;
                        } else {
                            sortColumn = index;
                            sortAsc = true;
                        }
                        renderTable();
                    });
                }
            });

            renderTable();
        });
    </script>

@endsection