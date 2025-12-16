@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="attendance-detail">
    <div class="attendance-detail__header">
        <h1 class="attendance-detail__title">
            勤怠詳細
        </h1>
    </div>
    <form action="{{ route('attendance.detail.store',$attendance->id) }}" method="post">
        @csrf
        <div class="attendance-detail__section">
            <table class="attendance-detail__table">
                <tr class="attendance-table__content">
                    <th class="attendance-table__header">
                        名前
                    </th>
                    <td class="attendance-table__date">
                        {{ $attendance->user->name }}
                    </td>
                </tr>
                <tr class="attendance-table__content">
                    <th class="attendance-table__header">
                        日付
                    </th>
                    <td class="attendance-table__date">
                        {{\Carbon\Carbon::parse($attendance->date)->format('Y年') }}
                    </td>
                    <td class="attendance-table__date">
                        {{\Carbon\Carbon::parse($attendance->date)->format('n月j日') }}
                    </td>
                </tr>
                <tr class="attendance-table__content">
                    <th class="attendance-table__header">
                        出勤 - 退勤
                    </th>
                    <td class="attendance-table__date">
                        <input type="time" name="new_clockIn" value="{{\Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}" class="form-control">
                    </td>
                    <td class="attendance-table__date">
                        <input type="time" name="new_clockOut" value="{{\Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}" class="form-control">
                    </td>
                </tr>
                <tr class="attendance-table__content">
                    <th class="attendance-table__header">
                        休憩
                    </th>
                    <td class="attendance-table__date">
                        @if(optional($attendance->application)->new_bleakIn &&optional($attendance->application)->new_bleakOut)
                        {{\Carbon\Carbon::parse($attendance->application->new_blakeIn)->format('H:i') }}
                    </td>
                        ~
                    <td class="attendance-table__date">
                        {{\Carbon\Carbon::parse($attendance->application->new_bleakOut)->format('H:i') }}
                    </td>
                        @else
                        -
                        @endif
                </tr>
                <tr class="attendance-table__content">
                    <th class="attendance-table__header">
                        休憩２
                    </th>
                    <td class="attendance-table__date">
                        @if(optional($attendance->application)->new_bleakIn &&optional($attendance->application)->new_bleakOut)
                        {{\Carbon\Carbon::parse($attendance->application->new_bleakIn2)->format('H:i') }}
                    </td>
                    <td class="attendance-table__date">
                        {{\Carbon\Carbon::parse($attendance->application->new_bleakOut2)->format('H:i') }}
                    </td>
                    @else
                    -
                    @endif
                </tr>
                <tr class="attendance-table__content">
                    <th class="attendance-table__header">
                        備考
                    </th>
                    <td class="attendance-table__date">
                        @if(optional($attendance->application)->reason)
                        
                        <textarea name="reason" rows="3" class="form-control" id="">
                            {{ old('reason', $attendance->reason) }}
                        </textarea>
                        {{ $attendance->application->reason }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="attendance-detail__footer">
            @if($attendance->status === 'pending')
            <p class="attention-text">
                *承認待ちのため修正できません
            </p>
            @else
            <div class="attendance-detail__btn">
    
                    <button type="submit" class="btn btn-primary">
                        修正
                    </button>
            </div>
            @endif
        </div>
    </form>
</div>
@endsection