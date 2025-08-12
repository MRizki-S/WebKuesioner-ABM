<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class SurveyController extends Controller
{
    public function show($slug)
    {
        $survey = Survey::with(['questions.choices'])->where('slug', $slug)->firstOrFail();

        // intro_schema di-decode dari JSON
        $introSchema = $survey->intro_schema ? json_decode($survey->intro_schema, true) : [];
        // dd($survey, $introSchema);
        return view('survey-user.index', compact('survey', 'introSchema'));
    }

    public function submit(Request $request, $slug)
    {
        $survey = Survey::where('slug', $slug)
            ->with(['questions.choices']) // eager load untuk efisiensi
            ->firstOrFail();

        $introSchema = json_decode($survey->intro_schema, true) ?? [];

        // Ambil nama field yang ada di intro schema
        $introFields = array_map(fn($field) => $field['name'] ?? null, $introSchema);
        $introFields = array_filter($introFields);

        // Ambil data meta dari request sesuai field yang diintroSchema
        $metaData = $request->only($introFields);

        // Jika ada nilai array, ambil elemen pertama (biasanya karena multiple select)
        foreach ($introFields as $field) {
            if (isset($metaData[$field]) && is_array($metaData[$field])) {
                $metaData[$field] = $metaData[$field][0] ?? null;
            }
        }
        // Simpan ke database, simpan meta sebagai JSON otomatis oleh Eloquent
        $response = $survey->responses()->create([
            'meta' => $metaData,
            'submitted_at' => Carbon::now(),
            'score' => 0,
        ]);


        $totalScore = 0;

        foreach ($survey->questions as $question) {
            $answerValue = $request->input('q' . $question->id);

            // simpan jawaban
            $response->answers()->create([
                'question_id' => $question->id,
                'answer'      => $answerValue,
            ]);

            // normalisasi jawaban user jadi string kecil tanpa spasi ujung
            $normalizedAnswer = mb_strtolower(trim((string) $answerValue));

            if ($question->type === 'choice_one') {
                // ambil semua nilai opsi yang ditandai benar
                $correctValues = $question->choices
                    ->where('is_correct', true)
                    ->pluck('value')
                    ->map(fn($v) => mb_strtolower(trim((string) $v)))
                    ->toArray();

                // jika jawaban user berada pada daftar nilai yang benar -> dapat poin
                if (!empty($normalizedAnswer) && in_array($normalizedAnswer, $correctValues, true)) {
                    $totalScore += (float) $question->points;
                }
            } else {
                // text / textarea: kalau diisi -> dapat poin (pakai points question)
                if (!empty($normalizedAnswer)) {
                    $totalScore += (float) $question->points;
                }
            }
        }

        // update score di responses
        $response->update(['score' => $totalScore]);

        Session::flash('success', 'Jawaban survey Anda berhasil disimpan');

        return redirect()->route('survey.show', $survey->slug);
    }
}
