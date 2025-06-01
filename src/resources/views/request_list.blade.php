@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request_list.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">申請一覧</h2>
    <div class="tabs">
        <a class="tab {{ $tab !== 'approved' ? 'selected' : ''}}" href="/stamp_correction_request/list">承認待ち</a>
        <a class="tab {{ $tab === 'approved' ? 'selected' : ''}}" href="/stamp_correction_request/list/?tab=approved">承認済み</a>
    </div>
    <table class="table">
        <tr class="table__row">
            <th class="table__header">状態</th>
            <th class="table__header">名前</th>
            <th class="table__header">対象日時</th>
            <th class="table__header">申請理由</th>
            <th class="table__header">申請日時</th>
            <th class="table__header">詳細</th>
        </tr>
        @if ($tab !== 'approved' && isset($correction_attendances))
            @foreach ($correction_attendances as $correction_attendance)
                <tr class="table__row">
                    <td class="table__detail">
                        {{ $correction_attendance->approval_status }}
                    </td>
                    <td class="table__detail">
                        {{ $correction_attendance->user->name }}
                    </td>
                    <td class="table__detail">
                        {{ \Carbon\Carbon::parse($correction_attendance->attendance->date)->format('Y/m/d') }}
                    </td>
                    <td class="table__detail">
                        {{ $correction_attendance->reason }}
                    </td>
                    <td class="table__detail">
                        {{ \Carbon\Carbon::parse($correction_attendance->requested_at)->format('Y/m/d') }}
                    </td>
                    <td class="table__detail"><a class="detail__link" href="/attendance/{{ $correction_attendance->id }}">詳細</a></td>
                </tr>
            @endforeach
        @endif
        @if ($tab === 'approved' && isset($approvals))
            @foreach ($approvals as $approval)
                <tr class="table__row">
                    <td class="table__detail">
                        {{ $approval->approval_status }}
                    </td>
                    <td class="table__detail">
                        {{ $approval->correction_attendance->user->id }}
                    </td>
                    <td class="table__detail">
                        {{ \Carbon\Carbon::parse($approval->correction_attendance->attendance->date)->format('Y/m/d') }}
                    </td>
                    <td class="table__detail">
                    {{ $approval->correction_attendance->reason }}
                    </td>
                    <td class="table__detail">
                        {{ \Carbon\Carbon::parse($approval->correction_attendance->requested_at)->format('Y/m/d') }}
                    </td>
                    <td class="table__detail"><a class="detail__link" href="/attendance/{{ $approval->correction_attendance->attendance->id }}">詳細</a></td>
                </tr>
            @endforeach
        @endif
    </table>
</div>
@endsection