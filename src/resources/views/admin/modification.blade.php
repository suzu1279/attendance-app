@extends('layouts.admin')

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
    <div class="attendance-detail__section">
        <table class="attendance-detail__table">
            <tr class="attendance-table__content">
                <th class="attendance-table__header">
                    名前
                </th>
                @foreach($applications as $application)
                <td class="attendance-table__date">
                    {{ $application->user->name }}
                </td>
                @endforeach
            </tr>
            <tr class="attendance-table__content">
                <th class="attendance-table__header">
                    日付
                </th>
                @foreach($applications as $application)
                <td class="attendance-table__date">
                    {{\Carbon\Carbon::parse($application->date)->format('Y年') }}
                </td>
                <td class="attendance-table__date">
                    {{\Carbon\Carbon::parse($application->date)->format('n月j日') }}
                </td>
                @endforeach
            </tr>
            <tr class="attendance-table__content">
                <th class="attendance-table__header">
                    出勤 - 退勤
                </th>
                @foreach($applications as $application)
                <td class="attendance-table__date">
                    <input type="time" name="new_clockIn" value="{{\Carbon\Carbon::parse($application->new_clockIn)->format('H:i') }}" class="form-control">
                </td>
                <td class="attendance-table__date">
                    <input type="time" name="new_clockOut" value="{{\Carbon\Carbon::parse($application->new_clockOut)->format('H:i') }}" class="form-control">
                </td>
                @endforeach
            </tr>
            <tr class="attendance-table__content">
                <th class="attendance-table__header">
                    休憩
                </th>
                @foreach($applications as $application)
                <td class="attendance-table__date">
                    @if(optional($application->attendance)->new_bleakIn &&optional($application->attendance)->new_bleakOut)
                    {{\Carbon\Carbon::parse(optional($application->attendance)->new_bleakIn)->format('H:i') }}
                </td>
                ~
                <td class="attendance-table__date">
                    {{\Carbon\Carbon::parse(optional($application->attendance)->new_bleakOut)->format('H:i') }}
                </td>
                @else
                -
                @endif
                @endforeach
            </tr>
            <tr class="attendance-table__content">
                <th class="attendance-table__header">
                    休憩２
                </th>
                @foreach($applications as $application)
                <td class="attendance-table__date">
                    @if(optional($application->attendance)->new_bleakIn &&optional($application->attendance)->new_bleakOut)
                    {{\Carbon\Carbon::parse(optional($application->attendance)->new_bleakIn)->format('H:i') }}
                </td>
                <td class="attendance-table__date">
                    {{\Carbon\Carbon::parse(optional($application->attendance)->new_bleakOut)->format('H:i') }}
                </td>
                @else
                -
                @endif
                @endforeach
            </tr>
            <tr class="attendance-table__content">
                <th class="attendance-table__header">
                    備考
                </th>
                @foreach($applications as $application)
                <td class="attendance-table__date">
                    @if(optional($attendance->application)->reason)
                    {{ $attendance->application->reason }}
                    @endif
                </td>
                @endforeach
            </tr>
        </table>
    </div>
    @foreach($applications as $application)
    <div class="attendance-detail__footer">
        @if($application->status === 'pending')
        <p class="attention-text">
            *承認待ちのため修正できません
        </p>
        @else
        <div class="attendance-detail__btn">
            <form action="{{ route('application.index') }}" method="get">
                <button type="submit" class="btn btn-primary">
                    修正
                </button>
            </form>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endsection