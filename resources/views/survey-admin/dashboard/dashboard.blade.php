@extends('layouts.app')

@section('header_content')
    <nav class="text-gray-600 text-sm mb-2" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ url('/') }}" class="text-purple-600 hover:text-purple-800 font-medium">Dashboard</a>
            </li>
            <li><span class="mx-2 text-gray-400">></span></li>
        </ol>
    </nav>


    <h1 class="text-2xl font-bold text-purple-700 mb-2">Dashboard</h1>
    <p class="text-gray-700">Kelola dan pantau semua survei Anda di satu tempat.</p>
@endsection

@section('content')
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div
                class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl shadow-lg p-6 flex flex-col justify-between hover:scale-[1.03] transition-transform duration-300">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold uppercase tracking-wide">Total Survey</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-70" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m12 0v-4a2 2 0 012-2h2a2 2 0 012 2v4M9 17h6" />
                    </svg>
                </div>
                <div class="text-4xl font-extrabold">{{ $totalSurvey }}</div>
            </div>

            <div
                class="bg-gradient-to-r bg-green-500 text-white rounded-xl shadow-lg p-6 flex flex-col justify-between hover:scale-[1.03] transition-transform duration-300">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold uppercase tracking-wide">Total Responses</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-70" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 11V7a4 4 0 10-8 0v4M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                    </svg>
                </div>
                <div class="text-4xl font-extrabold">{{ $totalResponses }}</div>
            </div>

            <div
                class="bg-gradient-to-r bg-yellow-400 text-white rounded-xl shadow-lg p-6 flex flex-col justify-between hover:scale-[1.03] transition-transform duration-300">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold uppercase tracking-wide">New Responses Today</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-70" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-4xl font-extrabold">{{ $newResponsesToday }}</div>
            </div>

            <div
                class="bg-gradient-to-r bg-blue-400 text-white rounded-xl shadow-lg p-6 flex flex-col justify-between hover:scale-[1.03] transition-transform duration-300">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold uppercase tracking-wide">Last Response Date</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-70" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-xl font-semibold">{{ $lastResponseDateFormatted }}</div>
            </div>

        </div>


    </div>
@endsection
