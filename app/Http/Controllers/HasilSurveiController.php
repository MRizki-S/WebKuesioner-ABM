<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use App\Exports\SurveyRekapExport;
use App\Exports\SurveyGeneralExport;
use Maatwebsite\Excel\Facades\Excel;

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



    // export hasil survey ke excel
    // public function exportRekap($slug)
    // {
    //     $survey = Survey::where('slug', $slug)
    //         ->with(['questions.answers.response'])
    //         ->firstOrFail();

    //     // Kumpulkan hasil rekap
    //     $rekap = [];

    //     foreach ($survey->questions as $question) {
    //         foreach ($question->answers as $answer) {
    //             $devisi = $answer->response->meta['devisi'] ?? 'Tidak Diketahui';

    //             if (!isset($rekap[$question->id][$devisi])) {
    //                 $rekap[$question->id][$devisi] = [
    //                     'pertanyaan'   => $question->text,
    //                     'devisi'       => $devisi,
    //                     'setuju'       => 0,
    //                     'tidak_setuju' => 0,
    //                     'jawaban_lain' => [],
    //                 ];
    //             }

    //             // Normalisasi jawaban ke lowercase
    //             $ans = strtolower(trim($answer->answer));

    //             if (in_array($ans, ['setuju', 'ya', 'puas'])) {
    //                 $rekap[$question->id][$devisi]['setuju']++;
    //             } elseif (in_array($ans, ['tidak_setuju', 'tidak', 'tidak puas', 'tidak setuju'])) {
    //                 $rekap[$question->id][$devisi]['tidak_setuju']++;
    //             } else {
    //                 $rekap[$question->id][$devisi]['jawaban_lain'][] = $answer->answer;
    //             }
    //         }
    //     }


    //     // Ubah array biar enak dipakai di export
    //     $data = [];
    //     foreach ($rekap as $pertanyaanId => $rows) {
    //         foreach ($rows as $row) {
    //             // kasih nomor urut
    //             $jawabanLain = [];
    //             foreach ($row['jawaban_lain'] as $i => $jwb) {
    //                 $jawabanLain[] = ($i + 1) . '. ' . $jwb;
    //             }

    //             $data[] = [
    //                 'pertanyaan'   => $row['pertanyaan'],
    //                 'devisi'       => $row['devisi'],
    //                 'setuju'       => $row['setuju'],
    //                 'tidak_setuju' => $row['tidak_setuju'],
    //                 'jawaban_lain' => implode("\n\n", $jawabanLain), // tetap ada spasi 2 baris antar jawaban
    //             ];
    //         }
    //     }

    //     // dd($data);

    //     return Excel::download(new SurveyRekapExport($data), 'rekap-survei-' . $survey->slug . '.xlsx');
    // }

    public function exportRekap($slug)
    {
        $survey = Survey::where('slug', $slug)
            ->with(['questions.answers.response'])
            ->firstOrFail();

        if ($slug === 'survey-kepuasan-karyawan') {
            // === FORMAT KHUSUS KARYAWAN ===
            $rekap = [];

            foreach ($survey->questions as $question) {
                foreach ($question->answers as $answer) {
                    $devisi = $answer->response->meta['devisi'] ?? 'Tidak Diketahui';

                    if (!isset($rekap[$question->id][$devisi])) {
                        $rekap[$question->id][$devisi] = [
                            'pertanyaan'   => $question->text,
                            'devisi'       => $devisi,
                            'setuju'       => 0,
                            'tidak_setuju' => 0,
                            'jawaban_lain' => [],
                        ];
                    }

                    // Normalisasi jawaban
                    $ans = strtolower(trim($answer->answer));
                    if (in_array($ans, ['setuju', 'ya', 'puas'])) {
                        $rekap[$question->id][$devisi]['setuju']++;
                    } elseif (in_array($ans, ['tidak_setuju', 'tidak', 'tidak puas', 'tidak setuju'])) {
                        $rekap[$question->id][$devisi]['tidak_setuju']++;
                    } else {
                        $rekap[$question->id][$devisi]['jawaban_lain'][] = $answer->answer;
                    }
                }
            }

            // Format final array
            $data = [];
            foreach ($rekap as $pertanyaanId => $rows) {
                foreach ($rows as $row) {
                    $jawabanLain = [];
                    foreach ($row['jawaban_lain'] as $i => $jwb) {
                        $jawabanLain[] = ($i + 1) . '. ' . $jwb;
                    }

                    $data[] = [
                        'pertanyaan'   => $row['pertanyaan'],
                        'devisi'       => $row['devisi'],
                        'setuju'       => $row['setuju'],
                        'tidak_setuju' => $row['tidak_setuju'],
                        'jawaban_lain' => implode("\n\n", $jawabanLain),
                    ];
                }
            }

            return Excel::download(
                new SurveyRekapExport($data),
                'rekap-survei-' . $survey->slug . '.xlsx'
            );
        } else {
            // === FORMAT GENERAL (CUSTOMER, PEMASARAN, DLL) ===
            $headers = [];
            $rows = [];

            // Ambil semua meta key dari semua response
            $metaKeys = [];
            foreach ($survey->questions->first()->answers as $dummy) {
                $metaKeys = array_merge($metaKeys, array_keys($dummy->response->meta ?? []));
            }
            $metaKeys = array_unique($metaKeys);

            // Ambil pertanyaan
            $questionHeaders = $survey->questions->pluck('text')->toArray();

            // Gabungkan header: intro schema (meta) + pertanyaan
            $headers = array_merge($metaKeys, $questionHeaders);

            // Bangun rows
            foreach ($survey->questions->first()->answers as $dummy) {
                $response = $dummy->response;

                $row = [];

                // Isi meta intro schema
                foreach ($metaKeys as $key) {
                    $row[$key] = $response->meta[$key] ?? '-';
                }

                // Isi jawaban pertanyaan
                foreach ($survey->questions as $q) {
                    $answer = $q->answers->where('response_id', $response->id)->first();
                    $row[$q->text] = $answer?->answer ?? '-';
                }

                $rows[] = $row;
            }

            return Excel::download(
                new SurveyGeneralExport($headers, $rows),
                'rekap-survei-' . $survey->slug . '.xlsx'
            );
        }
    }
}
