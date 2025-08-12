<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\QuestionChoice;
use Illuminate\Support\Facades\Session;

class PertanyaanSurveiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'type' => 'required|in:text,textarea,choice_one',
            'points' => 'required|numeric|min:0',
            'question' => 'required|string|max:65535',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
            'correct_choices' => 'nullable|array',
            'correct_choices.*' => 'integer',
        ]);

        // Ambil survey_id dari input form
        $survey_id = $request->input('survey_id');

        // Simpan pertanyaan baru
        $question = Question::create([
            'survey_id' => $survey_id,
            'type' => $request->type,
            'points' => $request->points,
            'text' => $request->question,
        ]);

        // Simpan pilihan jawaban jika tipe choice_one
        if ($request->type === 'choice_one' && $request->has('options')) {
            $options = $request->input('options');
            $correctIndexes = $request->input('correct_choices', []);

            foreach ($options as $index => $label) {
                QuestionChoice::create([
                    'question_id' => $question->id,
                    'label' => $label,
                    'value' => $label, // value sama dengan label
                    'is_correct' => in_array($index, $correctIndexes),
                ]);
            }
        }
        // Update total_points di tabel surveys (tambah point baru ke total existing)
        $survey = Survey::findOrFail($survey_id);
        $survey->increment('total_points', $request->points);

        // Set session flash message sukses
        Session::flash('success', 'Pertanyaan berhasil ditambahkan!');

        // Redirect ke halaman edit survey sesuai survey_id
        return redirect()->route('pertanyaan.edit', $survey->slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $survey = Survey::with('questions.choices')->where('slug', $slug)->firstOrFail();

        $introSchema = $survey->intro_schema ? json_decode($survey->intro_schema, true) : [];
        // dd($survey, $introSchema);
        return view('survey-admin.kategori-survei.pertanyaan.edit-PertanyaanSurvei', compact('survey', 'introSchema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:text,textarea,choice_one',
            'points' => 'required|numeric|min:0',
            'question' => 'required|string|max:65535',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
            'correct_choices' => 'nullable|array',
            'correct_choices.*' => 'integer',
        ]);

        // Ambil pertanyaan lama
        $question = Question::findOrFail($id);
        $oldPoints = $question->points; // poin lama
        $survey = $question->survey;    // langsung ambil relasi survey

        // Update data pertanyaan
        $question->update([
            'type' => $request->type,
            'points' => $request->points,
            'text' => $request->question,
        ]);

        // Kalau tipe pilihan ganda
        if ($request->type === 'choice_one') {
            $options = $request->input('options', []);
            $correctIndexes = $request->input('correct_choices', []);

            // Ambil pilihan lama
            $oldChoices = $question->choices()->get();

            // Update atau tambah pilihan
            foreach ($options as $index => $label) {
                $isCorrect = in_array($index, $correctIndexes);

                if (isset($oldChoices[$index])) {
                    $oldChoice = $oldChoices[$index];
                    if ($oldChoice->label !== $label || (bool)$oldChoice->is_correct !== $isCorrect) {
                        $oldChoice->update([
                            'label' => $label,
                            'value' => $label,
                            'is_correct' => $isCorrect,
                        ]);
                    }
                } else {
                    QuestionChoice::create([
                        'question_id' => $question->id,
                        'label' => $label,
                        'value' => $label,
                        'is_correct' => $isCorrect,
                    ]);
                }
            }

            // Hapus pilihan yang tidak ada di request
            if (count($options) < $oldChoices->count()) {
                $idsToKeep = $oldChoices->take(count($options))->pluck('id');
                QuestionChoice::where('question_id', $question->id)
                    ->whereNotIn('id', $idsToKeep)
                    ->delete();
            }
        } else {
            // Kalau bukan pilihan ganda, hapus semua pilihan lama
            QuestionChoice::where('question_id', $question->id)->delete();
        }

        // Update total_points di survey berdasarkan selisih
        $pointDifference = $request->points - $oldPoints;
        if ($pointDifference !== 0) {
            $survey->increment('total_points', $pointDifference);
        }

        Session::flash('success', 'Pertanyaan berhasil diperbarui!');
        return redirect()->route('pertanyaan.edit', $survey->slug);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::findOrFail($id);
        $survey = $question->survey;
        // dd($survey, $question);
        // Hapus semua pilihan jawaban terkait
        QuestionChoice::where('question_id', $question->id)->delete();

        // Hapus pertanyaan
        $question->delete();

        // Update total_points di survey
        $survey->decrement('total_points', $question->points);

        Session::flash('success', 'Pertanyaan berhasil dihapus!');
        return redirect()->route('pertanyaan.edit', $survey->slug);
    }

    // pertanyaan
    public function showQuestion($slug)
    {
        dd($slug);
    }
}
