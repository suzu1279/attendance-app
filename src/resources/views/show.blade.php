@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="attendance-detail">
    <h1 class="page-title">勤怠詳細</h1>

    @if(session('status'))
    <div class="alert-success">
        {{ session('status') }}
    </div>
    @endif
    @if($errors->any())
    <ul>
        @foreach($errors->all()as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
    <form action="{{ route('attendance.update',$attendance) }}" method="post">
        @csrf
        @method('put')
        <div class="attendance-detail__card">
            <div class="attendance-detail__row">
                <div class="attendance-detail__label">
                    名前
                </div>
                <div class="attendance-detail__value">
                    {{ $attendance->user->name }}
                </div>
            </div>
            <div class="attendance-detail__row">
                <div class="attendance-detail__label">
                    日付
                </div>
                <div class="attendance-detail__value attendance-detail__value--date">
                    <span>
                        {{ $attendance->date->format('Y年') }}
                    </span>
                    <span>
                        {{ $attendance->date->format('n月j日') }}
                    </span>
                </div>
            </div>
            <div class="attendance-detail__row">
                <div  class="attendance-detail__label">
                    出勤・退勤
                </div>
                <div class="attendance-detail__value attendance-detail__value--item--range">
                    <span>
                        {{ optional($attendance->new_clockIn)->format('H:i') }}
                    </span>
                    <span>
                        ~
                    </span>
                    <span>
                        {{ optional($attendance->new_clockOut)->format('H:i') }}
                    </span>
                </div>
            </div>
            <div class="attendance-detail__row">
                <div class="attendance-detail__label">
                    休憩
                </div>
                <div class="attendance-detail__value attendance-detail__value--time-range">
                    <span>
                        {{ optional($attendance->new_breakIn)->format('H:i') }}
                    </span>
                    <span>
                        ~
                    </span>
                    <span>
                        {{ optional($attendance->new_breakOut)->format(H:i) }}
                    </span>
                </div>
            </div>
            <div class="attendance-detail__row">
                <div class="attendance-detail__label">
                    休憩２
                </div>
                <div class="attendance-detail__value attendance-detail__value--time-range">
                    <span>
                        {{ optional($attendance->new_breakIn2)->format('H:i') }}
                    </span>
                    <span>
                        ~
                    </span>
                    <span>
                        {{ optional($attendance->new_breakOut2)->format('H:i') }}
                    </span>
                </div>
            </div>
            <div class="attendance-detail__row    attendance-detail__row-textarea">
                <div class="attendance-detail__label">
                    備考
                </div>
                <div   class="attendance-detail__value">
                    <div class="attendance-detail__note-box">
                        {{ $attendance->note }}
                    </div>
                </div>
            </div>
            <div class="attendance-detail__actions">
                <button type="submit" class="submit">
                    修正
                </button>
            </div>
        </div>    
    </form>
</div>
@endsection