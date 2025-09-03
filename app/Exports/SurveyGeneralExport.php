<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SurveyGeneralExport implements FromView, WithEvents
{
    protected $headers;
    protected $rows;

    public function __construct(array $headers, array $rows)
    {
        $this->headers = $headers;
        $this->rows    = $rows;
    }

    public function view(): View
    {
        return view('exports.survey-general', [
            'headers' => $this->headers,
            'rows'    => $this->rows,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();

                // Atur lebar kolom tetap
                $fixedWidth = 20; // ganti sesuai kebutuhan
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setWidth($fixedWidth);

                    // Aktifkan wrap text agar jawaban panjang turun ke bawah
                    $sheet->getStyle($col . '1:' . $col . $sheet->getHighestRow())
                        ->getAlignment()
                        ->setWrapText(true);
                }
            },
        ];
    }
}
