@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/daily.css') }}">
@endsection

@section('content')
<div class="attendance-page">
    <div class="attendance-header">
        <h1 class="daily">
            {{ $displayDate }}の勤怠
        </h1>
    </div>
    <div class="date-nav">
        <a href="{{ route('admin.attendance.daily',['date' => $prevDate]) }}">
            <-前日
        </a>
        <div class="current-date">
            <span class="calendar-icon">
                📅
            </span>
            <span>
                {{ $date->format('Y/m/d') }}
            </span>
        </div>
        <a href="{{ route('admin.attendance.daily',['date' => $nextDate]) }}">
            翌日->
        </a>
    </div>
    <table class="attendance-table">
        <thead class="table">
            <tr class="attendance-table__content">
                <th class="attendance-table__header">
                    名前
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
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
            <tr class="attendance-table__content">
                <td class="attendance-table__data">
                    {{ $attendance->user->name }}
                </td>
                <td class="attendance-table__data">
                    {{ $attendance->start_time ? $attendance->start_time->format('H:i'):'-'}}
                </td>
                <td class="attendance-table__data">
                    {{ $attendance->end_time ? $attendance->end_time->format('H:i'):'-' }}
                </td>
                <td class="attendance-table__data">
                    {{ $attendance->application->break_time ?? '-'}}
                </td>
                <td class="attendance-table__data">
                    {{ $attendance->total_time ?? '-'}}
                </td>
                <td class="attendance-table__data">
                    <a href="{{ route('admin.attendance.detail',$attendance->id) }}" class="btn btn-primary">
                        詳細
                    </a>
                </td>
            </tr>
            @empty
            <tr class="attendance-table__content">
                <td colspan="6" style="text-align:center;">
                    データがありません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection