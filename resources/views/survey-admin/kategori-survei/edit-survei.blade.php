@extends('layouts.app')

@section('header_content')
    <nav class="text-gray-600 text-sm mb-2" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ url('/survei') }}" class="text-purple-600 hover:text-purple-800 font-medium">Survei</a>
            </li>
            <li><span class="mx-2 text-gray-400">></span> Edit Survei</li>
        </ol>
    </nav>

    <h1 class="text-2xl font-bold text-purple-700 mb-2">Edit Survei</h1>
    <p class="text-gray-700">Ubah survei dengan mudah dan cepat.</p>
@endsection

@section('content')
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6 text-purple-700">Edit Survei</h1>


        @if ($errors->any())
            <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Danger</span>
                <div>
                    <span class="font-medium">Terjadi kesalahan validasi:</span>
                    <ul class="mt-1.5 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        <form action="{{ route('survei.update', $survey->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama survei --}}
            <div>
                <label for="name" class="block font-medium mb-1">Nama Survei</label>
                <input type="text" name="name" id="name" placeholder="Masukkan nama survei"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-purple-500"
                    required value="{{ old('name', $survey->name) }}">
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block font-medium mb-1">Deskripsi survei</label>
                <textarea name="description" id="description" rows="3" placeholder="Masukkan deskripsi survei"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-purple-500 resize-none"
                    required>{{ old('description', $survey->description) }}</textarea>
            </div>

            {{-- Intro Schema Dinamis --}}
            <div>
                <label class="block font-medium mb-2">Intro Schema</label>

                <div id="intro-schema-container" class="space-y-4">
                    @php
                        $introSchemas = old('intro_schema', json_decode($survey->intro_schema, true) ?? []);
                    @endphp

                    @foreach ($introSchemas as $i => $item)
                        <div class="intro-schema-item border p-4 rounded relative">
                            <button type="button"
                                class="remove-item absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-lg">&times;</button>

                            <div class="mb-3">
                                <label class="block mb-1 font-medium">Name</label>
                                <input type="text" name="intro_schema[{{ $i }}][name]"
                                    value="{{ $item['name'] ?? '' }}"
                                    class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Masukkan name"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="block mb-1 font-medium">Label</label>
                                <input type="text" name="intro_schema[{{ $i }}][label]"
                                    value="{{ $item['label'] ?? '' }}"
                                    class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Masukkan label"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="block mb-1 font-medium">Type</label>
                                <select name="intro_schema[{{ $i }}][type]"
                                    class="w-full border border-gray-300 rounded px-3 py-2 type-select" required>
                                    <option value="" disabled {{ empty($item['type']) ? 'selected' : '' }}>Pilih tipe
                                    </option>
                                    <option value="text" {{ ($item['type'] ?? '') === 'text' ? 'selected' : '' }}>Text
                                    </option>
                                    <option value="select" {{ ($item['type'] ?? '') === 'select' ? 'selected' : '' }}>
                                        Select (Dropdown)</option>
                                </select>
                            </div>

                            <div class="mb-3 options-container {{ ($item['type'] ?? '') === 'select' ? '' : 'hidden' }}">
                                <label class="block mb-1 font-medium">Options (pisahkan dengan koma)</label>
                                <input type="text" name="intro_schema[{{ $i }}][options]"
                                    class="w-full border border-gray-300 rounded px-3 py-2"
                                    placeholder="Contoh: Operasional,Keuangan,Pemasaran,Produksi"
                                    value="{{ isset($item['options']) && is_array($item['options']) ? implode(',', $item['options']) : '' }}">
                                <p class="text-sm text-gray-500 mt-1">Masukkan opsi dipisah koma, misal:
                                    Operasional,Keuangan,Pemasaran</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-intro-schema"
                    class="mt-3 bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition">
                    + Tambah Field Intro Schema
                </button>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-purple-700 text-white py-3 px-6 rounded hover:bg-purple-800 transition font-semibold">
                    Simpan Survei
                </button>
            </div>

        </form>
    </div>

    <script>
        (() => {
            let count = {{ count($introSchemas) }};

            const container = document.getElementById('intro-schema-container');
            const addBtn = document.getElementById('add-intro-schema');

            function toggleOptions(selectEl) {
                const parent = selectEl.closest('.intro-schema-item');
                const optionsDiv = parent.querySelector('.options-container');
                if (selectEl.value === 'select') {
                    optionsDiv.classList.remove('hidden');
                    optionsDiv.querySelector('input').required = true;
                } else {
                    optionsDiv.classList.add('hidden');
                    optionsDiv.querySelector('input').required = false;
                }
            }

            container.addEventListener('change', e => {
                if (e.target.classList.contains('type-select')) {
                    toggleOptions(e.target);
                }
            });

            container.addEventListener('click', e => {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('.intro-schema-item').remove();
                }
            });

            addBtn.addEventListener('click', () => {
                const newItem = document.createElement('div');
                newItem.className = 'intro-schema-item border p-4 rounded relative';
                newItem.innerHTML = `
                    <button type="button" class="remove-item absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-lg">&times;</button>

                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Name</label>
                        <input type="text" name="intro_schema[${count}][name]" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Masukkan name" required>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Label</label>
                        <input type="text" name="intro_schema[${count}][label]" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Masukkan label" required>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Type</label>
                        <select name="intro_schema[${count}][type]" class="w-full border border-gray-300 rounded px-3 py-2 type-select" required>
                            <option value="" disabled selected>Pilih tipe</option>
                            <option value="text">Text</option>
                            <option value="select">Select (Dropdown)</option>
                        </select>
                    </div>

                    <div class="mb-3 options-container hidden">
                        <label class="block mb-1 font-medium">Options (pisahkan dengan koma)</label>
                        <input type="text" name="intro_schema[${count}][options]" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Contoh: Operasional,Keuangan,Pemasaran,Produksi">
                        <p class="text-sm text-gray-500 mt-1">Masukkan opsi dipisah koma, misal: Operasional,Keuangan,Pemasaran</p>
                    </div>
                `;
                container.appendChild(newItem);
                count++;
            });
        })();
    </script>
@endsection
