@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/clock_in.css') }}">
@endsection

@section('content')
<div class="main">
    <div class="work__status">
        @if ($break_time && is_null($break_time->break_end))
            休憩中
        @elseif ($attendance && $attendance->clock_in && is_null($attendance->clock_out))
            出勤中
        @elseif ($attendance && $attendance->clock_out)
            退勤済
        @else
            勤務外
        @endif
    </div>
    <div class="date">
        {{ $today }}
    </div>
    <div class="time">
        {{ $time }}
    </div>
    <div class="time-log__button">
        @if ($break_time && is_null($break_time->break_end))
            <form action="/break/end" method="POST">
                @csrf
                <button type="submit" class="button break">休憩戻</button>
            </form>
        @elseif ($attendance && $attendance->clock_in && is_null($attendance->clock_out))
            <form action="/attendance/clock-out" method="POST">
                @csrf
                <button type="submit" class="button attendance">退勤</button>
            </form>
            <form action="/break/start" method="POST">
                @csrf
                <button type="submit" class="button break">休憩入</button>
            </form>
        @elseif ($attendance && $attendance->clock_out)
            <p class="clock-out__text">お疲れ様でした。</p>
        @else
            <form action="/attendance/clock-in" method="POST">
                @csrf
                <button type="submit" class="button attendance">出勤</button>
            </form>
        @endif
    </div>
</div>
@endsection