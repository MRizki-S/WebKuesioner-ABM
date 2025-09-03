@php
    // Style untuk header
    $th = 'border:1px solid #ccc; padding:10px; background:#a0a0a0dc;
           color:#fff; text-align:center; font-weight:bold; vertical-align:middle;
           min-width:120px; word-wrap:break-word;';

    // Style untuk isi tabel
    $td = 'border:1px solid #ccc; padding:8px; vertical-align:top;
           white-space: pre-line; word-wrap:break-word;';
@endphp

<table style="border-collapse: collapse; width:100%; font-family:Arial, sans-serif;
              font-size:13px; border:1px solid #ccc; table-layout:auto;">
    <thead>
        <tr>
            @foreach ($headers as $h)
                <th style="{{ $th }}">{{ $h }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $stripe = ['#fff', '#f9f9f9']; $i = 0; @endphp
        @foreach ($rows as $row)
            <tr style="background: {{ $stripe[$i % 2] }};"> @php $i++; @endphp
                @foreach ($headers as $h)
                    <td style="{{ $td }}">
                        @php
                            $val = $row[$h] ?? '';
                            if (is_array($val) || is_object($val)) {
                                $val = json_encode($val, JSON_UNESCAPED_UNICODE);
                            }
                        @endphp
                        {!! nl2br(e($val)) !!}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
