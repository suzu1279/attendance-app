@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<section class="staff-list">
    <h1 class="staff-list__title">
        | スタッフ一覧
    </h1>
    <table class="staff-table">
        <thead>
            <tr class="attendance-table__content">
                <th class="attendance-table__header">名前</th>
                <th class="attendance-table__header">メールアドレス</th>
                <th class="attendance-table__header">月次勤怠</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr class="attendance-table__content">
                <td class="attendance-table__date">{{ $user->name }}</td>
                <td class="attendance-table__date">{{ $user->email }}</td>
                <td class="attendance-table__date">
                    <a href="{{ route('admin.attendance.staff.show', ['id' => $user->id]) }}" class="staff-table__link">

                        詳細
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection