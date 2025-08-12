@extends('layouts.app')

@section('header_content')
    <h1 class="text-2xl font-bold text-purple-700 mb-2">Survei</h1>
    <nav class="text-gray-600 text-sm" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ url('/survei') }}" class="text-purple-600 hover:text-purple-800 font-medium">Survei</a>
            </li>
            <li><span class="mx-2 text-gray-400">></span></li>
            <li class="text-gray-800 font-semibold">{{ $survey->name }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Kiri: Form Tambah Pertanyaan -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Tambah Pertanyaan</h2>
            <form action="{{ route('pertanyaan.store') }}" method="POST" class="space-y-4">
                @csrf

                <input type="hidden" name="survey_id" value="{{ $survey->id }}">

                @if ($errors->any())
                    <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 " role="alert">
                        <svg class="shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
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


                <!-- Pilih Type Pertanyaan -->
                <div>
                    <label for="type" class="block mb-1 font-medium text-gray-700">Tipe Pertanyaan</label>
                    <select id="type" name="type"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-purple-500"
                        required>
                        <option value="" disabled selected>Pilih tipe pertanyaan</option>
                        <option value="text">Input Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="choice_one">Pilihan Ganda (Choice One)</option>
                    </select>
                </div>

                <!-- Input point -->
                <div>
                    <label for="points" class="block mb-1 font-medium text-gray-700">Point</label>
                    <input type="number" name="points" id="points" placeholder="Masukkan point"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-purple-500"
                        required min="0" step="0.01">
                </div>

                <!-- Input Pertanyaan -->
                <div>
                    <label for="question" class="block mb-1 font-medium text-gray-700">Pertanyaan</label>
                    <input type="text" name="question" id="question" placeholder="Masukkan pertanyaan"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-purple-500"
                        required>
                </div>

                <!-- Container untuk opsi pilihan ganda -->
                <div id="choice-options" class="space-y-3 hidden">
                    <label class="block mb-1 font-medium text-gray-700">Opsi Pilihan Ganda</label>

                    <div class="flex items-center gap-2">
                        <input type="text" name="options[]" placeholder="Opsi 1"
                            class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-purple-500">
                        <label class="flex items-center gap-1 cursor-pointer select-none">
                            <input type="checkbox" name="correct_choices[]" value="1" class="w-4 h-4 text-purple-600">
                            <span class="text-gray-700 text-sm">Benar</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="text" name="options[]" placeholder="Opsi 2"
                            class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-purple-500">
                        <label class="flex items-center gap-1 cursor-pointer select-none">
                            <input type="checkbox" name="correct_choices[]" value="0" class="w-4 h-4 text-purple-600">
                            <span class="text-gray-700 text-sm">Benar</span>
                        </label>
                    </div>

                    <button type="button" id="addOption" class="mt-2 text-purple-600 hover:underline text-sm font-medium">+
                        Tambah Opsi</button>
                </div>

                <button type="submit"
                    class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition">Simpan
                    Pertanyaan</button>
            </form>
        </div>

        <!-- Kanan: Card untuk Preview / Hasil -->
        <div class="bg-white shadow-lg rounded-lg p-6 overflow-y-auto max-h-[500px]">
            <h1 class="text-2xl font-bold text-purple-700 mb-2">{{ $survey->name }}</h1>
            <p class="text-gray-700 mb-0">{{ $survey->description }}</p>
            <div class="text-gray-700 mb-6">
                Total Score: <span class="font-semibold text-green-700">{{ $survey->total_points }}</span>
            </div>

            <div class="space-y-6">
                {{-- Intro Schema --}}
                @foreach ($introSchema as $fieldName => $fieldConfig)
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            {{ is_array($fieldConfig) ? $fieldConfig['label'] ?? '' : $fieldConfig }}
                        </label>

                        @if (is_array($fieldConfig) && isset($fieldConfig['options']))
                            <select name="{{ $fieldName }}" required
                                class="w-full rounded border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">-- Pilih --</option>
                                @foreach ($fieldConfig['options'] as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" name="{{ $fieldName }}" required
                                placeholder="Input jawaban Anda di sini"
                                class="w-full rounded border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" />
                        @endif
                    </div>
                @endforeach

                {{-- Questions --}}
                @foreach ($survey->questions as $index => $question)
                    <div class="mb-6 flex items-start justify-between">
                        <div class="flex-1">
                            <h2 class="mb-1">{{ $index + 1 }}. {{ $question->text }} <span
                                    class="text-green-700">({{ $question->points }}) </span></h2>

                            @if ($question->type === 'choice_one')
                                <div class="space-y-2">
                                    @foreach ($question->choices as $choice)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" disabled
                                                @if ($choice->is_correct) checked @endif>
                                            {{ $choice->label }}
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($question->type === 'textarea')
                                <textarea name="q{{ $question->id }}" rows="4" placeholder="Input jawaban Anda di sini" required
                                    class="block p-2.5 w-full rounded border-gray-300 focus:ring-purple-500 focus:border-purple-500 resize-none"></textarea>
                            @else
                                <input type="text" name="q{{ $question->id }}"
                                    placeholder="Input jawaban Anda di sini" required
                                    class="w-full rounded border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" />
                            @endif
                        </div>

                        <div class="flex flex-col items-center ml-4 space-y-2">
                            {{-- Tombol Edit --}}
                            <a href="#" data-modal-target="modal-edit-{{ $question->id }}"
                                data-modal-toggle="modal-edit-{{ $question->id }}"
                                class="text-blue-600 hover:text-blue-800" title="Edit Pertanyaan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z" />
                                </svg>
                            </a>


                            {{-- Tombol Delete --}}
                            <a href="#" data-modal-target="modal-delete-{{ $question->id }}"
                                data-modal-toggle="modal-delete-{{ $question->id }}"
                                class="text-red-600 hover:text-red-800" title="delete Pertanyaan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7L5 7M6 7L6 19a2 2 0 002 2h8a2 2 0 002-2L18 7M10 11v6M14 11v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach


                <div class="flex justify-end">
                    <button type="submit"
                        class="text-white bg-purple-700 hover:bg-purple-800 rounded-lg text-sm px-5 py-2.5">
                        Submit
                    </button>
                </div>
            </div>
        </div>

    </div>



    {{-- modal pop up edit pertanyaan --}}
    @foreach ($survey->questions as $item)
        <div id="modal-edit-{{ $item->id }}"
            class="hidden fixed inset-0 z-50 flex justify-center items-center overflow-auto bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-semibold">Edit Pertanyaan</h3>
                    <button type="button" data-modal-toggle="modal-edit-{{ $item->id }}">×</button>
                </div>

                <form action="{{ route('pertanyaan.update', $item->id) }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Pertanyaan --}}
                    <div>
                        <label>Pertanyaan</label>
                        <input type="text" name="question" value="{{ old('question', $item->text) }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    {{-- Points --}}
                    <div>
                        <label>Points</label>
                        <input type="number" name="points" value="{{ old('points', $item->points) }}" min="0"
                            step="0.01" class="w-full border rounded px-3 py-2" required>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label>Tipe</label>
                        <select name="type" id="type-{{ $item->id }}" class="w-full border rounded px-3 py-2"
                            required>
                            <option value="text" {{ $item->type == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="textarea" {{ $item->type == 'textarea' ? 'selected' : '' }}>Textarea</option>
                            <option value="choice_one" {{ $item->type == 'choice_one' ? 'selected' : '' }}>Pilihan Ganda
                            </option>
                        </select>
                    </div>

                    {{-- Opsi pilihan ganda (muncul kalau tipe choice_one) --}}
                    <div id="choice-options-{{ $item->id }}"
                        class="space-y-3 {{ $item->type != 'choice_one' ? 'hidden' : '' }}">
                        <label>Opsi Pilihan Ganda</label>

                        @foreach ($item->choices as $index => $choice)
                            <div class="flex items-center gap-2">
                                <input type="text" name="options[]" value="{{ $choice->label }}"
                                    class="flex-1 border rounded px-3 py-2">
                                <label class="flex items-center gap-1">
                                    <input type="checkbox" name="correct_choices[]" value="{{ $index }}"
                                        {{ $choice->is_correct ? 'checked' : '' }}>
                                    <span>Benar</span>
                                </label>
                                <button type="button" class="remove-option text-red-500">✕</button>
                            </div>
                        @endforeach

                        <button type="button" class="addOption text-purple-600 text-sm font-medium">+ Tambah
                            Opsi</button>
                    </div>

                    <div class="flex justify-end ">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach


    {{-- Modal Delete Pertanyaan --}}
    @foreach ($survey->questions as $item)
        <div id="modal-delete-{{ $item->id }}"
            class="hidden fixed inset-0 z-50 flex justify-center items-cente bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
                {{-- Header --}}
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-semibold text-red-600">Hapus Pertanyaan</h3>
                    <button type="button" data-modal-toggle="modal-delete-{{ $item->id }}"
                        class="text-gray-500 hover:text-gray-700">×</button>
                </div>

                {{-- Body --}}
                <div class="mt-4">
                    <p class="text-gray-700">Apakah kamu yakin ingin menghapus pertanyaan ini?</p>
                    <p class="mt-2 p-3 bg-gray-100 rounded text-gray-800 font-medium">
                        "{{ $item->text }}"
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Aksi ini akan menghapus semua opsi jawaban terkait.</p>
                </div>

                {{-- Footer --}}
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" data-modal-toggle="modal-delete-{{ $item->id }}"
                        class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">
                        Batal
                    </button>

                    <form action="{{ route('pertanyaan.destroy', $item->id) }}" method="POST">
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



    {{-- script edit pertanyaan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($survey->questions as $item)
                let typeSelect{{ $item->id }} = document.getElementById('type-{{ $item->id }}');
                let choiceOptions{{ $item->id }} = document.getElementById(
                    'choice-options-{{ $item->id }}');

                typeSelect{{ $item->id }}.addEventListener('change', function() {
                    if (this.value === 'choice_one') {
                        choiceOptions{{ $item->id }}.classList.remove('hidden');
                    } else {
                        choiceOptions{{ $item->id }}.classList.add('hidden');
                    }
                });

                // Add option button
                choiceOptions{{ $item->id }}.querySelector('.addOption').addEventListener('click', function() {
                    let optionCount = choiceOptions{{ $item->id }}.querySelectorAll(
                        'input[name="options[]"]').length;

                    let wrapper = document.createElement('div');
                    wrapper.className = 'flex items-center gap-2';

                    wrapper.innerHTML = `
                <input type="text" name="options[]" placeholder="Opsi ${optionCount + 1}" class="flex-1 border rounded px-3 py-2">
                <label class="flex items-center gap-1">
                    <input type="checkbox" name="correct_choices[]" value="${optionCount}">
                    <span>Benar</span>
                </label>
                <button type="button" class="remove-option text-red-500">✕</button>
            `;

                    choiceOptions{{ $item->id }}.insertBefore(wrapper, this);
                });

                // Remove option
                choiceOptions{{ $item->id }}.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-option')) {
                        e.target.closest('div').remove();
                    }
                });
            @endforeach
        });
    </script>

    {{-- script tambah pertanyaan --}}
    <script>
        const typeSelect = document.getElementById('type');
        const choiceOptions = document.getElementById('choice-options');
        const addOptionBtn = document.getElementById('addOption');

        typeSelect.addEventListener('change', () => {
            if (typeSelect.value === 'choice_one') {
                choiceOptions.classList.remove('hidden');
                resetOptions();
            } else {
                choiceOptions.classList.add('hidden');
                resetOptions();
            }
        });

        addOptionBtn.addEventListener('click', () => {
            const optionCount = choiceOptions.querySelectorAll('input[name="options[]"]').length;

            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-center gap-2';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'options[]';
            input.placeholder = `Opsi ${optionCount + 1}`;
            input.className =
                'flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-purple-500';

            const label = document.createElement('label');
            label.className = 'flex items-center gap-1 cursor-pointer select-none';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'correct_choices[]';
            checkbox.value = optionCount;
            checkbox.className = 'w-4 h-4 text-purple-600';

            const span = document.createElement('span');
            span.className = 'text-gray-700 text-sm';
            span.textContent = 'Benar';

            label.appendChild(checkbox);
            label.appendChild(span);

            wrapper.appendChild(input);
            wrapper.appendChild(label);

            choiceOptions.insertBefore(wrapper, addOptionBtn);
        });

        function resetOptions() {
            // Hapus semua input opsi kecuali 2 pertama
            const inputs = choiceOptions.querySelectorAll('div.flex.items-center.gap-2');
            inputs.forEach((div, idx) => {
                if (idx >= 2) div.remove();
            });

            // Reset nilai input dan checkbox di 2 opsi pertama
            const optionInputs = choiceOptions.querySelectorAll('input[name="options[]"]');
            const correctCheckboxes = choiceOptions.querySelectorAll('input[name="correct_choices[]"]');
            optionInputs.forEach((input, idx) => {
                input.value = '';
                input.placeholder = `Opsi ${idx + 1}`;
                if (correctCheckboxes[idx]) correctCheckboxes[idx].checked = false;
                if (correctCheckboxes[idx]) correctCheckboxes[idx].value = idx;
            });
        }
    </script>
@endsection
