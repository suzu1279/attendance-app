@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="attendance-form__content">
    <div class="mt-4">
        <form class="attendance-button" action="{{ route('attendance.detail',['id'=>auth()->id()]) }}" method="get">
            <span class="label">
                {{ $attendanceLabel}}
            </span>
            <header>
                <div class="time-wrapper">
                   <p class="attendance-date"> 
                    {{ $formattedDate }}
                   </p>
                    <br>
                    <p class="attendance-time">
                    {{ $formattedTime }}
                    </p>
                </div>
            </header>
        </form>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @php
        $status = $attendance->status ?? 'off';

        $statusLabels = [
            'off' => '勤務外',
            'working' => '出勤中',
            'breaking' => '休憩中',
            'finished' => '退勤済',
        ];
        $statusLabel = $statusLabels[$status]??'';
        @endphp

        <p>
            <strong>{{ $statusLabel }}</strong>
        </p>

        @if (Auth::user()->attendance_status == 0)
        <form action="{{ route('attendance.start') }}" method="post">
            @csrf
            <button type="submit" class="btn-primary">出勤</button>
        </form>
        @elseif (Auth::user()->attendance_status == 1)
        <form action="{{ route('attendance.end') }} " method="post" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn-danger">退勤</button>
        </form>
        <form action="{{ route('attendance.break.start') }}" method="post" style="display:inline-block; margin-left: 8px;">
            @csrf
            <button type="submit" class="btn-warning">休憩入</button>
        </form>
        @elseif(Auth::user()->attendance_status === 2)
        <form action="{{ route('attendance.break.end') }}" method="post">
            @csrf
            <button type="submit" class="btn-success">休憩終了</button>
        </form>
        @else
        <p>お疲れ様でした</p>
        @endif
    </div>
</div>
@endsection