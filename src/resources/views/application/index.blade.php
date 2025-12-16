@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/application/index.css') }}">
@endsection

@section('content')
<div class="application-content">
    <h1 class="page-title">
        申請一覧
    </h1>
    <div class="application-tabs">
        <a href="{{ route('application.index',['tab' => 'pending']) }}" class="tab{{ $tab === 'pending' ? 'is-active' : ''}}">
            承認待ち
        </a>
        <a href="{{ route('application.index',['tab' => 'approved']) }}" class="tab{{ $tab === 'approved' ?'is-active' : ''}}">
            承認済み
        </a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr class="table-content">
                    <th class="table-header">状態</th>
                    <th class="table-header">名前</th>
                    <th class="table-header">対象日時</th>
                    <th class="table-header">申請理由</th>
                    <th class="table-header">申請日時</th>
                    <th class="table-header">詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr class="table-content">
                    <td class="table-date">
                        {{ $application->status }}
                    </td>
                    <td class="table-date">
                        {{ $application->user->name }}
                    </td>
                    <td class="table-date">
                        {{ $application->target_date->format('Y/m/d') }}
                    </td>
                    <td class="table-date">
                        {{ $application->reason }}
                    </td>
                    <td class="table-date">
                        {{ $application->created_at->format('Y/m/d') }}
                    </td>
                    <td class="table-date">
                        <a href="{{ route('attendance.detail', $application->id) }}" class="detail-link">
                            詳細
                        </a>
                    </td>
                </tr>
                <tr class="table-content">
                    <td colspan="6" class="text-center">
                        申請はありません
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">
        {{ $applications->appends(['tab'=>$tab])->links() }}
    </div>
</div>
@endsection