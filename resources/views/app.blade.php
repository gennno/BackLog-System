<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPP Mining</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- AOS for animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen">
        <!-- Loading Overlay -->
<div id="page-loader"
     class="fixed inset-0 bg-white z-50 flex items-center justify-center opacity-0 transition-opacity duration-300 pointer-events-none">
    <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-blue-500 border-solid"></div>
</div>
    <div class="flex flex-col md:flex-row min-h-screen w-full">
        @yield('content')
    </div>

<script>
    window.addEventListener("beforeunload", function () {
        const loader = document.getElementById("page-loader");
        loader.classList.remove("opacity-0", "pointer-events-none");
        loader.classList.add("opacity-100");
    });
</script>

</body>
</html>
