@extends('layouts.app')

@section('header_content')
    <nav class="text-gray-600 text-sm mb-2" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ url('/hasil-survei') }}" class="text-purple-600 hover:text-purple-800 font-medium">Hasil
                    Survei</a>
            </li>
            <li><span class="mx-2 text-gray-400">></span></li>
            <li class="text-gray-800 font-semibold">{{ $survey->name }}</li>
        </ol>
    </nav>


    <h1 class="text-xl text-purple-700 mb-2">Hasil Survei - {{ $survey->name }}</h1>
    <p class="text-gray-700">
        Ringkasan hasil survei
    </p>
@endsection


@section('content')
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-purple-100 p-4 rounded-lg shadow text-center">
                <h2 class="text-lg font-semibold text-purple-700">Total Pertanyaan</h2>
                <p class="text-xl">{{ $survey->questions_count }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg shadow text-center">
                <h2 class="text-lg font-semibold text-green-700">Total Skor</h2>
                <p class="text-xl">{{ $totalScore }}</p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg shadow text-center">
                <h2 class="text-lg font-semibold text-yellow-700">Rata-rata Skor</h2>
                <p class="text-xl">{{ number_format($averageScore, 2) }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded-lg shadow text-center">
                <h2 class="text-lg font-semibold text-blue-700">Max Point (Semua Benar)</h2>
                <p class="text-xl">{{ $maxPoints }}</p>
            </div>
            <div class="bg-indigo-100 p-4 rounded-lg shadow text-center">
                <h2 class="text-lg font-semibold text-indigo-700">Total Responden</h2>
                <p class="text-xl">{{ $survey->responses_count }}</p>
            </div>
        </div>

        {{-- table response --}}
        <h2 class="text-xl font-semibold mb-4">Daftar Respon</h2>
        <div class="mb-4 flex justify-end ">
            <a href="{{ route('hasil-survei.export-rekap', $survey->slug) }}"
                class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                Export Rekap
            </a>
        </div>
        <div class="overflow-x-auto max-h-[400px] rounded">

            <table class="w-full text-sm text-left rtl:text-center text-gray-500 overflow-x-auto overflow-y-auto">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">No.</th>
                        <th scope="col" class="px-6 py-3 text-center">Dikirim Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-center">Score</th>
                        <th scope="col" class="px-6 py-3 text-center">Preview</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($responses as $index => $response)
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $responses->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ \Carbon\Carbon::parse($response->submitted_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $response->score }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('hasil-survei.response.show', [$survey->slug, $response->id]) }}"
                                    class="text-blue-600 hover:underline">Lihat</a>
                            </td>
                        </tr>
                    @empty


                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada respon</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $responses->links() }}
        </div>


    </div>
@endsection
