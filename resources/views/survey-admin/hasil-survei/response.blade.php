@extends('layouts.app')

@section('header_content')
    <nav class="text-gray-600 text-sm mb-2" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ url('/hasil-survei') }}" class="text-purple-600 hover:text-purple-800 font-medium">Hasil
                    Survei</a>
            </li>
            <li><span class="mx-2 text-gray-400">></span></li>
            <li>
                <a href="{{ route('hasil-survei.show', $survey->slug) }}"
                    class="text-purple-600 hover:text-purple-800 font-medium">
                    {{ $survey->name }}
                </a>
            </li>
            <li><span class="mx-2 text-gray-400">></span></li>
            <li class="text-gray-800 font-semibold">Response</li>
        </ol>
    </nav>

    <h1 class="text-xl text-purple-700 mb-2">Respon Dari Survei - {{ $survey->name }}</h1>
    <p class="text-gray-700">
        Detail respon dari survei ini.
    </p>
@endsection



@section('content')
    <div class=" rounded-lg flex justify-center md:flex-row flex-col gap-4">
        <div class="w-full lg:max-w-[800px]">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h1 class="text-2xl font-bold text-purple-700 mb-2">{{ $survey->name }}</h1>
                <p class="text-gray-700">{{ $survey->description }}</p>

                <p class="mt-4 text-gray-800 font-semibold">
                    Score: <span class="text-purple-700">{{ $response->score }}</span> / <span>{{ $maxPoints }}</span>
                </p>
            </div>
        </div>


        <div class="w-full px-4  overflow-y-auto max-h-[500px]">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                {{-- Intro Schema (read only) --}}
                @foreach ($introSchema as $fieldConfig)
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            {{ $fieldConfig['label'] ?? '' }}
                        </label>

                        @php
                            $fieldName = $fieldConfig['name'] ?? '';
                            $value = $response->meta[$fieldName] ?? '';
                        @endphp

                        @if (($fieldConfig['type'] ?? '') === 'select' && isset($fieldConfig['options']))
                            <select name="{{ $fieldName }}" disabled
                                class="w-full rounded border-gray-300 bg-gray-100 cursor-not-allowed shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">-- Pilih --</option>
                                @foreach ($fieldConfig['options'] as $opt)
                                    <option value="{{ $opt }}" {{ $value === $opt ? 'selected' : '' }}>
                                        {{ $opt }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" name="{{ $fieldName }}" disabled value="{{ $value }}"
                                class="w-full rounded border-gray-300 bg-gray-100 cursor-not-allowed shadow-sm focus:border-purple-500 focus:ring-purple-500" />
                        @endif
                    </div>
                @endforeach

                {{-- Questions (read only) --}}
                @foreach ($questions as $index => $question)
                    @php
                        $answerValue = $answers[$question->id]->answer ?? '';
                    @endphp
                    <div class="mb-4">
                        <h2 class="mb-1">
                            {{ $index + 1 }}. {{ $question->text }}
                            <span class="text-green-700 font-semibold">({{ $question->points }})</span>
                        </h2>


                        @if ($question->type === 'choice_one')
                            <div class="space-y-2">
                                @foreach ($question->choices as $choice)
                                    @php
                                        $isUserChoice = $answerValue === $choice->value;
                                        $isCorrectChoice = $choice->is_correct;
                                        $labelClass = '';

                                        if ($isUserChoice && $isCorrectChoice) {
                                            $labelClass = 'bg-green-200'; // Jawaban benar yang dipilih user
                                        } elseif ($isUserChoice && !$isCorrectChoice) {
                                            $labelClass = 'bg-red-200'; // Jawaban salah yang dipilih user
                                        }
                                    @endphp

                                    <label class="flex items-center gap-2 rounded px-2 py-1 {{ $labelClass }}">
                                        <input type="radio" name="q{{ $question->id }}" value="{{ $choice->value }}"
                                            disabled {{ $isUserChoice ? 'checked' : '' }}
                                            class="text-purple-600 cursor-not-allowed">
                                        {{ $choice->label }}
                                    </label>
                                @endforeach
                            </div>
                        @elseif ($question->type === 'textarea')
                            <textarea name="q{{ $question->id }}" rows="4" disabled
                                class="block p-2.5 w-full rounded border-gray-300 bg-gray-100 cursor-not-allowed resize-none focus:ring-purple-500 focus:border-purple-500">{{ $answerValue }}</textarea>
                        @else
                            <input type="text" name="q{{ $question->id }}" disabled value="{{ $answerValue }}"
                                class="w-full rounded border-gray-300 bg-gray-100 cursor-not-allowed shadow-sm focus:border-purple-500 focus:ring-purple-500" />
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
