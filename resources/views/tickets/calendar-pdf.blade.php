<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Biļešu kalendārs</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            font-size: 12px;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 12px;
        }

        h2 {
            font-size: 14px;
            margin: 16px 0 8px;
        }

        table { 
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #f2f2f2;
            text-align: left;
        }

        .muted {
            color: #777;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .badge-open {
            background: #e8f4f8;
        }

        .badge-in_progress {
            background: #fff3cd;
        }

        .badge-resolved {
            background: #d1f2eb;
        }

        .badge-closed {
            background: #f8d7da;
        }
    </style>
</head>
<body>
    <h1>Biļešu kalendārs</h1>

    @php
        $monthNames = [
            1 => 'Janvāris', 2 => 'Februāris', 3 => 'Marts', 4 => 'Aprīlis',
            5 => 'Maijs', 6 => 'Jūnijs', 7 => 'Jūlijs', 8 => 'Augusts',
            9 => 'Septembris', 10 => 'Oktobris', 11 => 'Novembris', 12 => 'Decembris'
        ];
    @endphp

    @foreach ($months as $monthData)
        <h2>{{ $monthNames[$monthData['month']->month] }} {{ $monthData['month']->year }}</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 18%;">Datums</th>
                    <th>Biļetes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthData['days'] as $day)
                    <tr>
                        <td>{{ $day['date']->format('d.m.Y') }}</td>
                        <td>
                            @if ($day['tickets']->count() === 0)
                                <span class="muted">Nav biļešu</span>
                            @else
                                @foreach ($day['tickets'] as $ticket)
                                    <div>
                                        <strong>#{{ $ticket->id }}</strong> {{ $ticket->title }}
                                        <span class="badge badge-{{ $ticket->status }}">{{ $ticket->status }}</span>
                                        <span class="muted">({{ $ticket->priority }})</span>
                                    </div>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
