<table style="border-collapse: collapse; width:100%; font-family:Arial, sans-serif; font-size:13px; border:1px solid #ccc;">
    <thead>
        <tr style="  text-align:center;">
            <th style="padding: 5px; color:#fff;  background:#a0a0a0dc; border:1px solid #ccc; padding:8px;">Pertanyaan</th>
            <th style="padding: 5px; color:#fff; background:#a0a0a0dc; border:1px solid #ccc; padding:8px;">Devisi</th>
            <th style="padding: 5px; color:#fff; background:#a0a0a0dc; border:1px solid #ccc; padding:8px;">Setuju</th>
            <th style="padding: 5px; color:#fff; background:#a0a0a0dc; border:1px solid #ccc; padding:8px;">Tidak Setuju</th>
            <th style="padding: 5px; color:#fff; background:#a0a0a0dc; border:1px solid #ccc; padding:8px;">Jawaban Lain</th>
        </tr>
    </thead>
    <tbody>
        @php $lastQuestion = null; $rowIndex = 0; @endphp
        @foreach ($data as $row)
            @php $rowIndex++; @endphp
            <tr>
                {{-- Pertanyaan cuma ditampilkan sekali, row-span untuk banyak devisi --}}
                @if ($lastQuestion !== $row['pertanyaan'])
                    @php
                        $rowspan = collect($data)->where('pertanyaan', $row['pertanyaan'])->count();
                    @endphp
                    <td rowspan="{{ $rowspan }}"
                        style="border:1px solid #ccc; padding:10px; text-align:center; vertical-align:middle; font-weight:bold;">
                        {{ $row['pertanyaan'] }}
                    </td>
                    @php $lastQuestion = $row['pertanyaan']; @endphp
                @endif

                <td style="border:1px solid #ccc; padding:8px; vertical-align:middle;">{{ $row['devisi'] }}</td>
                <td style="border:1px solid #ccc; padding:8px; text-align:center; vertical-align:middle;">{{ $row['setuju'] }}</td>
                <td style="border:1px solid #ccc; padding:8px; text-align:center; vertical-align:middle;">{{ $row['tidak_setuju'] }}</td>
                <td style="border:1px solid #ccc; padding:8px; vertical-align:top;">
                    @if (!empty($row['jawaban_lain']))
                        <ul style="margin:0; padding-left:18px; list-style-type: disc;">
                            @foreach (explode("\n", $row['jawaban_lain']) as $i => $jwb)
                                @if(trim($jwb) !== '')
                                    <li style="margin-bottom:4px;">{{ trim($jwb) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <span style="color:#999; font-style:italic;">-</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
