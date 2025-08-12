@extends('layouts.app')

@section('header_content')
    <nav class="text-gray-600 text-sm mb-2" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ url('/hasil-survei') }}" class="text-purple-600 hover:text-purple-800 font-medium">Hasil
                    Survei</a>
            </li>
            <li><span class="mx-2 text-gray-400">></span></li>
        </ol>
    </nav>


    <h1 class="text-2xl font-bold text-purple-700 mb-2">Hasil Survei per Kategori</h1>
    <p class="text-gray-700">
        Ringkasan hasil survei berdasarkan kategori.
    </p>
@endsection


@section('content')
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="relative overflow-x-auto sm:rounded-lg">

            <table class="w-full text-sm text-left rtl:text-center text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Survei</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Pertanyaan</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Score</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Responden</th>
                        <th scope="col" class="px-6 py-3 text-center">Hasil Survei</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surveys as $survey)
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $survey->name }}
                            </th>
                            <td class="px-6 py-4">{{ $survey->description }}</td>
                            <td class="px-6 py-4 text-center">{{ $survey->questions_count }}</td>
                            <td class="px-6 py-4 text-center">{{ $survey->total_points }}</td>
                            <td class="px-6 py-4 text-center">{{ $survey->responses_count }}</td>
                            <td class="px-6 py-4 text-center flex justify-center items-center">
                                <a href="{{ route('hasil-survei.show', $survey->slug) }}"
                                    class="font-medium text-blue-600 hover:underline">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada survei</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
