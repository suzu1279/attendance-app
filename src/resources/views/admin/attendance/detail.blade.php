@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="attendance-detail">
    <form action="{{route('admin.attendance.approve',$attendance->id) }}" method="post">
        @csrf
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
                    <td class="attendance-table_date">
                        <input type="time" name="start_time" value="{{ old('start_time',$attendance->start_time?->format('H:i')) }}">
                    </td>
                    @error('start_time')
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror
                    <td class="attendance-table__date">
                        <input type="time" name="end_time" value="{{ old('end_time',$attendance->end_time?->format('H:i')) }}">
                    </td>
                    @error('end_time')
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror
                </tr>
                <tr class="attendance-table__content">
                    <th class="attendance-table__header">
                        休憩
                    </th>
                    <td class="attendance-table__date">
                        <input type="time" name="new_bleakIn" value="{{ old('new_bleakIn',$attendance->new_bleakIn?->format('H:i')) }}">
                    </td>
                    @error('new_bleakIn')
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror
                    <td class="attendance-table__date">
                        <input type="time" name="new_bleakOut" value="{{ old('new_bleakOut',$attendance->new_bleakOut?->format('H:i')) }}">
                    </td>
                    @error('new_bleakOut')
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror
                </tr>
                <tr class="attendance-table__content">
                    <th class="attendance-table__header">
                        休憩２
                    </th>
                    <td class="attendance-table__date">
                        <input type="time" name="new_bleakIn" value="{{ old('new_bleakIn',$attendance->new_bleakIn?->format('H:i')) }}">
                    </td>
                    @error('new_bleakIn')
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror
                    <td class="attendance-table__date">
                        <input type="time" name="new_bleakOut" value="{{ old('new_bleakOut',$attendance->new_bleakOut?->format('H:i')) }}">
                    </td>
                    @error('new_bleakOut')
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror
                </tr>
                <tr class="attendance-table__content">
                    <th class="attendance-table__header">
                        備考
                    </th>
                    <td class="attendance-table__date">
                        <textarea name="reason" id="">
                        {{ old('reason',$attendance->reason) }}
                        </textarea>
                    </td>
                    @error('reason')
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror
                </tr>
            </table>
        </div>
        @if($attendance->status === 'pending')
        <p class="attention-text">
           *承認待ちのため修正できません
        </p>
        @else
        <div class="attendance-detail__btn">
            <button type="button" class="btn btn-primary" id="approve-button">
                承認
            </button>
        </div>
        @endif
        <script>
            document.addEventListener('DOMContentLoaded',()=>{
                const btn = document.getElementById('approve-button');

                if(!btn) return;

                btn.addEventListener('click',()=>{
                    btn.textContent ='承認済み',
                    btn.classList.add('btn-approved');
                    btn.disabled = true;
                });
            });
        </script>
    </form>
</div>
@endsection