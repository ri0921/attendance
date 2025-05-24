@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">勤怠詳細</h2>
    @if ($correction)
    <div class="table-wrapper">
        <table class="table">
            <tr class="table__row">
                <th class="table__header">名前</th>
                <td class="table__detail">
                    {{ $attendance->user->name }}
                </td>
            </tr>
            <tr class="table__row">
                <th class="table__header">日付</th>
                <td class="table__detail">
                    <div class="detail__group">
                        <div class="year">
                            {{ \Carbon\Carbon::parse($attendance->date)->format('Y年')}}
                        </div>
                        <div class="date">6月1日</div>
                    </div>
                </td>
            </tr>
            <tr class="table__row">
                <th class="table__header">出勤・退勤</th>
                <td class="table__detail">
                    <div class="detail__group">
                        09:00
                        <span>〜</span>
                        18:00
                    </div>
                </td>
            </tr>
            <tr class="table__row">
                <th class="table__header">休憩</th>
                <td class="table__detail">
                    <div class="detail__group">
                        12:00
                        <span>〜</span>
                        13:00
                    </div>
                </td>
            </tr>
            <tr class="table__row">
                <th class="table__header">備考</th>
                <td class="table__detail">
                    電車遅延のため
                </td>
            </tr>
        </table>
    </div>
    <div class="alert-message">* 承認待ちのため修正はできません。</div>

    @else
    <form action="/attendance/{id}/request" method="POST">
        @csrf
        <div class="table-wrapper">
            <table class="table">
                <tr class="table__row">
                    <th class="table__header">名前</th>
                    <td class="table__detail">
                        {{ $attendance->user->name }}
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">日付</th>
                    <td class="table__detail">
                        <div class="detail__group">
                            <div class="year">
                                {{ \Carbon\Carbon::parse($attendance->date)->format('Y年')}}
                            </div>
                            <div class="date">
                                {{ \Carbon\Carbon::parse($attendance->date)->format('m月d日')}}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">出勤・退勤</th>
                    <td class="table__detail">
                        <div class="detail__group">
                            <input class="input__time" type="time" name="clock_in" value="{{ old('clock_in', \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')) }}" readonly>
                            <span>〜</span>
                            <input class="input__time" type="time" name="clock_out" value="{{ old('clock_out', \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')) }}">
                        </div>
                    </td>
                </tr>
                @foreach ($break_times as $index => $break_time)
                <tr class="table__row">
                    <th class="table__header">休憩{{ $index === 0 ? '' : $index + 1 }}</th>
                    <td class="table__detail">
                        <div class="detail__group">
                            <input class="input__time" type="time" name="break_start" value="{{ \Carbon\Carbon::parse($break_time->break_start)->format('H:i') }}">
                            <span>〜</span>
                            <input class="input__time" type="time" name="break_end" value="{{ \Carbon\Carbon::parse($break_time->break_end)->format('H:i') }}">
                        </div>
                    </td>
                </tr>
                @endforeach
                <tr class="table__row">
                    <th class="table__header">休憩{{ count($break_times) ? count($break_times) + 1 : '' }}</th>
                    <td class="table__detail">
                        <div class="detail__group">
                            <input class="input__time" type="time" name="break_start" value="">
                            <span>〜</span>
                            <input class="input__time" type="time" name="break_end" value="">
                        </div>
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">備考</th>
                    <td class="table__detail">
                        <textarea class="textarea" name="reason">{{ old('reason') }}</textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div class="button">
            <button class="edit__button">修正</button>
        </div>
    </form>
    @endif
</div>
@endsection