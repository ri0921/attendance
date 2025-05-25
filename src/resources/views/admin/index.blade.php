@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">{{ $current->year }}年{{ $current->month }}月{{ $current-> day }}日の勤怠</h2>
    <div class="date-selector">
        <a href="{{ url('/admin/attendance/list') }}?date={{ $prev_date }}" class="arrow"><img class="arrow__image" src="{{ asset('img/left.png') }}">前日</a>
        <div class="date"><img class="calendar__image" src="{{ asset('img/calendar.png') }}">{{ \Carbon\Carbon::parse($current)->format('Y/m/d') }}</div>
        <a href="{{ url('/admin/attendance/list') }}?date={{ $next_date }}" class="arrow">翌日<img class="arrow__image" src="{{ asset('img/right.png') }}"></a>
    </div>
    <table class="table">
        <tr class="table__row">
            <th class="table__header">名前</th>
            <th class="table__header">出勤</th>
            <th class="table__header">退勤</th>
            <th class="table__header">休憩</th>
            <th class="table__header">合計</th>
            <th class="table__header">詳細</th>
        </tr>
        @isset($attendances)
            @foreach ($attendances as $attendance)
                <tr class="table__row">
                    <td class="table__detail">
                        {{ $attendance->user->name }}
                    </td>
                    <td class="table__detail">
                        {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
                    </td>
                    <td class="table__detail">
                        @isset($attendance->clock_out)
                        {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
                        @endisset
                    </td>
                    <td class="table__detail">
                        {{ $attendance->total_break_formatted }}
                    </td>
                    <td class="table__detail">
                        {{ $attendance->total_work_formatted }}
                    </td>
                    <td class="table__detail"><a class="detail__link" href="/attendance/{{ $attendance->id }}">詳細</a></td>
                </tr>
            @endforeach
        @endisset
    </table>
</div>
@endsection