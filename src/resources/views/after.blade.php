@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="attendance-form__content">
    <div class="mt-4">
        <form class="attendance-button" action="/attendance/{id}" method="post">
            @csrf

            <button class="leaving-work__button-submit" type="submit">
                退勤
            </button>
            <button class="breaking-button__submit" type="submit">
                休憩入
            </button>
        </form>
    </div>
</div>
@endsection