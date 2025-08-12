<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        $surveys = [
            [
                'name' => 'Survey Karyawan Umum',
                'description' => 'Survey internal untuk karyawan umum.',
                'intro_schema' => json_encode([
                    [
                        'label' => 'Devisi',
                        'name' => 'devisi',
                        'type' => 'select',
                        'options' => ['Operasional', 'Keuangan', 'Pemasaran', 'Produksi']
                    ]
                ]),
                'questions' => [
                    [
                        'text' => 'Apakah Anda puas dengan fasilitas kantor?',
                        'type' => 'choice_one',
                        'points' => 10,
                        'choices' => ['Puas', 'Tidak Puas'],
                    ],
                    [
                        'text' => 'Apakah Anda setuju dengan kebijakan kerja saat ini?',
                        'type' => 'choice_one',
                        'points' => 10,
                        'choices' => ['Setuju', 'Tidak Setuju'],
                    ],
                    [
                        'text' => 'Saran / Masukan untuk perbaikan.',
                        'type' => 'textarea',
                        'points' => 0,
                    ],
                ],
            ],
            [
                'name' => 'Survey Devisi Pemasaran',
                'description' => 'Evaluasi performa devisi pemasaran.',
                'intro_schema' => json_encode([
                    [
                        'label' => 'Nama Karyawan',
                        'name' => 'nama_karyawan',
                        'type' => 'text',
                    ]
                ]),
                'questions' => [
                    [
                        'text' => 'Apakah target penjualan tercapai bulan ini?',
                        'type' => 'choice_one',
                        'points' => 10,
                        'choices' => ['Ya', 'Tidak'],
                    ],
                    [
                        'text' => 'Apa kendala yang dihadapi?',
                        'type' => 'textarea',
                        'points' => 0,
                    ],
                ],
            ],
            [
                'name' => 'Survey User / Customer',
                'description' => 'Feedback dari customer setelah membeli unit.',
                'intro_schema' => json_encode([
                    [
                        'label' => 'Nama',
                        'name' => 'nama',
                        'type' => 'text',
                    ],
                    [
                        'label' => 'Blok Rumah',
                        'name' => 'blok_rumah',
                        'type' => 'text',
                    ]
                ]),
                'questions' => [
                    [
                        'text' => 'Apakah Anda puas terhadap pelayanan kami?',
                        'type' => 'choice_one',
                        'points' => 10,
                        'choices' => ['Puas', 'Tidak Puas'],
                    ],
                    [
                        'text' => 'Apakah Anda akan merekomendasikan kami?',
                        'type' => 'choice_one',
                        'points' => 10,
                        'choices' => ['Ya', 'Tidak'],
                    ],
                    [
                        'text' => 'Saran / Kritik untuk perbaikan kami.',
                        'type' => 'textarea',
                        'points' => 0,
                    ],
                ],
            ],
        ];

        foreach ($surveys as $surveyData) {
            $survey = Survey::create([
                'name' => $surveyData['name'],
                'slug' => Str::slug($surveyData['name']),
                'description' => $surveyData['description'],
                'intro_schema' => $surveyData['intro_schema'] ?? null,
            ]);

            foreach ($surveyData['questions'] as $qData) {
                $question = $survey->questions()->create([
                    'text' => $qData['text'],
                    'type' => $qData['type'],
                    'points' => $qData['points'] ?? 0,
                ]);

                if ($qData['type'] === 'choice_one' && isset($qData['choices'])) {
                    foreach ($qData['choices'] as $label) {
                        $question->choices()->create([
                            'label' => $label,
                            'value' => $label,
                        ]);
                    }
                }
            }
        }
    }
}
