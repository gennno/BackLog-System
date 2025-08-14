@extends('app')

@section('content')
<div class="w-full min-h-screen flex items-center justify-center px-4 bg-gray-100">
    <div class="bg-white w-full max-w-sm sm:max-w-md p-6 sm:p-10 rounded-xl shadow-md sm:shadow-lg">
        <!-- Header -->
        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center text-gray-800 mb-4 leading-snug">
            KPP Mining <br class="block sm:hidden" /> Backlog System
        </h2>

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('img/logo.png') }}" alt="Logo KPP" class="h-14 sm:h-20 object-contain">
        </div>

        <!-- Form -->
<form action="{{ route('login') }}" method="POST" class="space-y-4 sm:space-y-5">
    @csrf
            <div>
                <label for="email" class="block text-sm sm:text-base text-gray-700 font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="password" class="block text-sm sm:text-base text-gray-700 font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" placeholder="********" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
<div class="flex items-center justify-between">
    <label class="inline-flex items-center">
        <input type="checkbox" name="remember" class="form-checkbox text-blue-600">
        <span class="ml-2 text-sm text-gray-600">Remember me</span>
    </label>
</div>


    <button type="submit"
        class="w-full bg-blue-600 text-white py-2 sm:py-2.5 rounded-lg text-sm sm:text-base font-semibold hover:bg-blue-700 transition duration-200">
        Sign In
    </button>

        </form>
    </div>
</div>
@endsection
