@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="attendance-container">
    <div class="attendance-header">
        <h1 class="attendance-title">
            勤怠一覧
        </h1>
    </div>
    <div class="attendance-period">
        <a href="{{ route('attendance.list',['year'=>$prev->year, 'month' => $prev->month]) }}" class="month-link">
            <-前月
        </a>

        <span class="text">
           🗓️ {{ $current->format('Y/m') }}
        </span>

        <a href="{{ route('attendance.list',['year' => $next->year,'month' => $next->month]) }}" class="month-link">
            翌月->
        </a>
    </div>
    <div class="attendance-table__wrapper">
        <table class="attendance-table">
            <tr class="table-header">
                <th class="attendance-table__header">
                    日付
                </th>
                <th class="attendance-table__header">
                    出勤
                </th>
                <th class="attendance-table__header">
                    退勤
                </th>
                <th class="attendance-table__header">
                    休憩
                </th>
                <th class="attendance-table__header">
                    合計
                </th>
                <th class="attendance-table__header">
                    詳細
                </th>
            </tr>
            <tbody>
                @foreach ($days as $day)
                @php
                /** @var \Carbon\Carbon $date */
                $date = $day['carbon'];
                $attendance = $day['attendance'];
                $w = ['日','月','火','水','木','金','土'][$date->dayOfWeek];
                @endphp
                <tr class="table-date">
                    <td class="attendance-table__date">
                        {{ $date->format('m/d') }} ({{ $w }})
                    </td>
                    <td class="attendance-table__date">
                        {{ optional($attendance)->start_time?->format('H:i') }}
                    </td>
                    <td class="attendance-table__date">
                        {{ optional($attendance)->end_time?->format('H:i') }}
                    </td>
                    <td class="attendance-table__date">
                        @if($attendance)
                        {{ $attendance->total_break_minutes }}
                        @endif
                    </td>
                    <td class="attendance-table__date">
                        {{ optional($attendance)->total_duration }}
                    </td>
                    <td class="attendance-table__date">
                        @if ($attendance)
                        <a href="{{ route('attendance.detail',$attendance->id) }}" class="detail-link">
                            詳細
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection