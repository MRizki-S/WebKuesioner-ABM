<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class HasilSurveiController extends Controller
{
    public function index()
    {
        $surveys = Survey::withCount(['questions', 'responses']) // hitung pertanyaan & response
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($surveys);

        return view('survey-admin.hasil-survei.index', compact('surveys'));
    }

    public function show($slug)
    {
        $survey = Survey::withCount(['questions', 'responses'])
            ->with('questions') // eager load questions only, jangan eager load semua responses karena bisa besar
            ->where('slug', $slug)
            ->firstOrFail();

        // Ambil responses dengan paginate dan urut berdasarkan tanggal submit terbaru
        $responses = $survey->responses()->orderBy('submitted_at', 'desc')->paginate(10);

        $totalScore = $survey->responses->sum('score');
        $averageScore = $survey->responses->avg('score');
        $maxPoints = $survey->questions->sum('points');

        return view('survey-admin.hasil-survei.show', [
            'survey' => $survey,
            'responses' => $responses, // kirim responses paginasi ke view
            'totalScore' => $totalScore,
            'averageScore' => $averageScore,
            'maxPoints' => $maxPoints,
        ]);
    }


    public function responses($slug, $id)
    {
        $survey = Survey::where('slug', $slug)->firstOrFail();
        $response = $survey->responses()->with('answers')->findOrFail($id);

        // Parse intro_schema supaya bisa dipakai di view
        $introSchema = json_decode($survey->intro_schema, true) ?? [];

        // Load pertanyaan supaya bisa looping di view
        $questions = $survey->questions;

        // Index answers by question_id supaya akses lebih mudah
        $answers = $response->answers->keyBy('question_id');

        // Hitung skor maksimal (jumlah points semua pertanyaan)
        $maxPoints = $questions->sum('points');

        return view('survey-admin.hasil-survei.response', compact(
            'survey',
            'response',
            'introSchema',
            'questions',
            'answers',
            'maxPoints'
        ));
    }
}
