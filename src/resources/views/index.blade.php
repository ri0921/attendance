@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">勤怠一覧</h2>
    <div class="month-selector">
        <a class="arrow" href="{{ url('/attendance/list') }}?month={{ $prev_month }}">
            <img class="arrow__image" src="{{ asset('img/left.png') }}">前月
        </a>
        <div class="month">
            <img class="calendar__image" src="{{ asset('img/calendar.png') }}">{{ $current_month }}
        </div>
        <a class="arrow" href="{{ url('/attendance/list') }}?month={{ $next_month }}">
            翌月<img class="arrow__image" src="{{ asset('img/right.png') }}">
        </a>
    </div>
    <table class="attendance-table">
        <tr class="table__row">
            <th class="table__header">日付</th>
            <th class="table__header">出勤</th>
            <th class="table__header">退勤</th>
            <th class="table__header">休憩</th>
            <th class="table__header">合計</th>
            <th class="table__header">詳細</th>
        </tr>
        @foreach ($days as $day)
            @php
                $dateKey = $day->toDateString();
                $attendance = $attendances[$dateKey] ?? null;
            @endphp
            <tr class="table__row">
                <td class="table__detail">
                    {{ $day->isoFormat('MM/DD(ddd)') }}
                </td>
                <td class="table__detail">
                    @isset($attendance->clock_in)
                        {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
                    @endisset
                </td>
                <td class="table__detail">
                    @isset($attendance->clock_out)
                        {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
                    @endisset
                </td>
                <td class="table__detail">
                    {{ optional($attendance)->total_break_formatted }}
                </td>
                <td class="table__detail">
                    {{ optional($attendance)->total_work_formatted }}
                </td>
                <td class="table__detail">
                    @if ($attendance)
                        <a class="detail__link" href="/attendance/{{ $attendance->id }}">詳細</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
@endsection