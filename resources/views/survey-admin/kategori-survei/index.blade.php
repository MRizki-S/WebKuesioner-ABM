@extends('layouts.app')

@section('header_content')
    <nav class="text-gray-600 text-sm mb-2" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ url('/survei') }}" class="text-purple-600 hover:text-purple-800 font-medium">Survei</a>
            </li>
            <li><span class="mx-2 text-gray-400">></span></li>
        </ol>
    </nav>

    <h1 class="text-2xl font-bold text-purple-700 mb-2">Survei</h1>
    <p class="text-gray-700">Buat dan kelola survei Anda sebelum dibagikan kepada responden.</p>
@endsection

@section('content')
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="relative overflow-x-auto sm:rounded-lg">
            <div>
                <div class="flex justify-end items-center mb-4">
                    <a href="{{ route('survei.create') }}"
                        class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">+ Buat Survei</a>
                </div>
            </div>

            <table class="w-full text-sm text-left rtl:text-center text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Survei</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Pertanyaan</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Score</th>
                        <th scope="col" class="px-6 py-3 text-center">Copy Link</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
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
                            <td class="px-6 py-4 text-center">
                                <button onclick="copyToClipboard('{{ url('/survey/' . $survey->slug) }}')"
                                    class="text-purple-600 hover:text-purple-800" title="Copy Link">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"
                                        class="w-5 h-5 fill-current">
                                        <path
                                            d="M451.5 160C434.9 160 418.8 164.5 404.7 172.7C388.9 156.7 370.5 143.3 350.2 133.2C378.4 109.2 414.3 96 451.5 96C537.9 96 608 166 608 252.5C608 294 591.5 333.8 562.2 363.1L491.1 434.2C461.8 463.5 422 480 380.5 480C294.1 480 224 410 224 323.5C224 322 224 320.5 224.1 319C224.6 301.3 239.3 287.4 257 287.9C274.7 288.4 288.6 303.1 288.1 320.8C288.1 321.7 288.1 322.6 288.1 323.4C288.1 374.5 329.5 415.9 380.6 415.9C405.1 415.9 428.6 406.2 446 388.8L517.1 317.7C534.4 300.4 544.2 276.8 544.2 252.3C544.2 201.2 502.8 159.8 451.7 159.8zM307.2 237.3C305.3 236.5 303.4 235.4 301.7 234.2C289.1 227.7 274.7 224 259.6 224C235.1 224 211.6 233.7 194.2 251.1L123.1 322.2C105.8 339.5 96 363.1 96 387.6C96 438.7 137.4 480.1 188.5 480.1C205 480.1 221.1 475.7 235.2 467.5C251 483.5 269.4 496.9 289.8 507C261.6 530.9 225.8 544.2 188.5 544.2C102.1 544.2 32 474.2 32 387.7C32 346.2 48.5 306.4 77.8 277.1L148.9 206C178.2 176.7 218 160.2 259.5 160.2C346.1 160.2 416 230.8 416 317.1C416 318.4 416 319.7 416 321C415.6 338.7 400.9 352.6 383.2 352.2C365.5 351.8 351.6 337.1 352 319.4C352 318.6 352 317.9 352 317.1C352 283.4 334 253.8 307.2 237.5z" />
                                    </svg>
                                </button>
                            </td>



                            <td class="px-6 py-4 text-center flex justify-center gap-4">
                                <a href="{{ route('survei.edit', $survey->slug) }}"
                                    class="font-medium text-yellow-600 hover:underline">Edit</a>
                                <a href="{{ route('pertanyaan.edit', $survey->slug) }}"
                                    class="font-medium text-blue-600 hover:underline">Pertanyaan</a>
                                {{-- Tombol Delete --}}
                                <a href="#" data-modal-target="modal-delete-{{ $survey->id }}"
                                    data-modal-toggle="modal-delete-{{ $survey->id }}"
                                    class="text-red-600 hover:text-red-800" title="delete Pertanyaan">
                                    Hapus
                                </a>
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

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "success",
                    title: "Link berhasil disalin!",
                    showConfirmButton: false,
                    timer: 1500,
                    customClass: {
                        popup: 'text-sm py-2 px-3' // Kecilkan teks dan padding
                    }
                });
            }).catch(err => {
                console.error('Gagal menyalin link:', err);
            });
        }
    </script>


    {{-- Modal Delete Pertanyaan --}}
    @foreach ($surveys as $item)
        <div id="modal-delete-{{ $item->id }}"
            class="hidden fixed inset-0 z-50 flex justify-center items-cente bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
                {{-- Header --}}
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-semibold text-red-600">Hapus Survei</h3>
                    <button type="button" data-modal-toggle="modal-delete-{{ $item->id }}"
                        class="text-gray-500 hover:text-gray-700">×</button>
                </div>

                {{-- Body --}}
                <div class="mt-4">
                    <p class="text-gray-700">Apakah kamu yakin ingin menghapus survei ini?</p>
                    <p class="mt-2 p-3 bg-gray-100 rounded text-gray-800 font-medium">
                        "{{ $item->name }}"
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Aksi ini akan menghapus semua opsi pertanyaan dan jawaban terkait.</p>
                </div>

                {{-- Footer --}}
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" data-modal-toggle="modal-delete-{{ $item->id }}"
                        class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">
                        Batal
                    </button>

                    <form action="{{ route('survei.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
