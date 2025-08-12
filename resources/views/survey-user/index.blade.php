<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Karyawan ABM</title>

    {{-- favicon --}}
    <link rel="icon" href="{{ asset('assets/img/logo-abm2.png') }}" type="image/x-icon">

    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flowbite -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />


    {{-- sweet alert cdn --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

    <div class="bg-purple-700 w-full h-[100px]"></div>

    <div class="flex justify-center">
        <div class="w-full -mt-20 px-4 lg:max-w-[800px]">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h1 class="text-2xl font-bold text-purple-700 mb-2">{{ $survey->name }}</h1>
                <p class="text-gray-700">{{ $survey->description }}</p>
            </div>
        </div>
    </div>

    <div class="flex justify-center">
        <div class="w-full px-4 lg:max-w-[800px]">

            {{-- Jika ada pesan sukses, tampilkan pesan & sembunyikan form --}}
            @if (Session::has('success'))
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded relative mb-6 text-center">
                    <strong class="font-bold text-lg">✅ Jawaban sudah dikirim!</strong>
                    <p class="mt-2">{{ Session::get('success') }}</p>
                </div>
            @else
                <form action="{{ route('survey.submit', $survey->slug) }}" method="POST"
                    class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    @csrf

                    {{-- Intro Schema --}}
                    @foreach ($introSchema as $fieldConfig)
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                {{ $fieldConfig['label'] ?? '' }}
                            </label>

                            @if (($fieldConfig['type'] ?? '') === 'select' && isset($fieldConfig['options']))
                                <select name="{{ $fieldConfig['name'] ?? '' }}" required
                                    class="w-full rounded border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($fieldConfig['options'] as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" name="{{ $fieldConfig['name'] ?? '' }}" required
                                    placeholder="Input jawaban Anda di sini"
                                    class="w-full rounded border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" />
                            @endif
                        </div>
                    @endforeach


                    {{-- Questions --}}
                    @foreach ($survey->questions as $index => $question)
                        <div class="mb-4">
                            <h2>{{ $index + 1 }}. {{ $question->text }}</h2>

                            @if ($question->type === 'choice_one')
                                <div class="space-y-2">
                                    @foreach ($question->choices as $choice)
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="q{{ $question->id }}"
                                                value="{{ $choice->value }}" class="text-purple-600" required>
                                            {{ $choice->label }}
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($question->type === 'textarea')
                                <textarea name="q{{ $question->id }}" rows="4" placeholder="Masukan jawaban Anda di sini" required
                                    class="block p-2.5 w-full rounded border-gray-300 focus:ring-purple-500 focus:border-purple-500 resize-none"></textarea>
                            @else
                                <input type="text" name="q{{ $question->id }}"
                                    placeholder="Input jawaban Anda di sini" required
                                    class="w-full rounded border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" />
                            @endif
                        </div>
                    @endforeach

                    <div class="mb-2 flex justify-end">
                        <button type="submit"
                            class="text-white bg-purple-700 hover:bg-purple-800 rounded-lg text-sm px-5 py-2.5">
                            Submit
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>


    {{-- js alert --}}
    <script>
        @if (Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ Session::get('success') }}',
                showConfirmButton: false,
                timer: 3000 // Notifikasi akan hilang otomatis dalam 3 detik
            });
        @elseif (Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ Session::get('error') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>


    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>
