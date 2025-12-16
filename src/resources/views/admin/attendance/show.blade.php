@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="attendance-container">
    <header class="attendance-header">
        <div class="attendance-title">
            <h1 class="attendance-header__title">
                {{ $user->name}}さんの勤怠
            </h1>
        </div>
    </header>
    <div class="month-nav">
        <a class="month-nav__button month-nav__button--prev" href="{{ route('admin.attendance.staff.show',['id'=>$user->id,'month'=>$prevMonth->format('Y-m')]) }}">
            <- 前月
        </a>
        <div class="month-nav__current">
            📅{{ $currentMonth->format('Y/m')}}
        </div>
        <a class="month-nav__button month-nav__button--next" href="{{ route('admin.attendance.staff.show',['id'=>$user->id,'month'=>$nextMonth->format('Y-m')]) }}">
            翌月 ->
        </a>
    </div>
    <div class="attendance-table__container">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th class="attendanace-table__header">日付</th>
                    <th class="attendance-table__header">出勤</th>
                    <th class="attendance-table__header">退勤</th>
                    <th class="attendance-table__header">休憩</th>
                    <th class="attendance-table__header">合計</th>
                    <th class="attendance-table__header">詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($days as $day)
                @php
                /**@var\Carbon\Carbon $date */
                $date = $day['date'];
                $record = $day['attendance'];
                $w =$weekdayMap[$date->dayOfWeek]??'';
                @endphp
                <tr>
                    <td class="attendance-table__date">
                        {{ $date->format('m/d')}} ({{$w}})
                    </td>
                    <td class="attendance-table__date">
                        @if($record && $record->start_time)
                        {{\Carbon\Carbon::parse($record->start_time)->format('H:i')}}
                        @endif
                    </td>
                    <td class="attendance-table__date">
                        @if($record && $record->end_time)
                        {{\Carbon\Carbon::parse($record->end_time)->format('H:i') }}
                        @endif
                    </td>
                    <td class="attendance-table__date">
                        @if($record && $record->break_time)
                        {{ $record->break_time}}
                        @endif
                    </td>
                    <td class="attendance-table__date">
                        @if($record && $record->total_time)
                        {{ $record->total_time}}
                        @endif
                    </td>
                    <td class="attendance-table__date">
                        @if($record)
                        <a href="{{ route('admin.attendance.detail',['id'=>$record->id]) }}">
                            詳細
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="attendance-footer">
        <form action="{{ route('admin.attendance.export.csv',['user'=>$user->id]) }}" method="get">
            <input type="hidden" name="month" value="{{ $currentMonth->format('Y-m') }}">
            <button type="submit" class="csv-button">
                CSV出力
            </button>
        </form>
    </div>
</div>
@endsection