@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">勤怠詳細</h2>
    <form action="/attendance/{{ $attendance->id }}/update" method="post">
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
                            <input class="input__time" type="time" name="clock_in" value="{{ old('clock_in', \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')) }}">
                            <span>〜</span>
                            <input class="input__time" type="time" name="clock_out" value="{{ old('clock_out', \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')) }}">
                        </div>
                        <div class="form__error">
                            @error('clock_in')
                                {{ $message }}
                            @enderror
                            @error('clock_out')
                                {{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                @foreach ($break_times as $index => $break_time)
                    <tr class="table__row">
                        <th class="table__header">休憩{{ $index === 0 ? '' : $index + 1 }}</th>
                        <td class="table__detail">
                            <div class="detail__group">
                                <input class="input__time" type="time" name="break_time[{{ $index }}][break_start]" value="{{ old("break_time.$index.break_start", \Carbon\Carbon::parse($break_time->break_start)->format('H:i')) }}">
                                <span>〜</span>
                                <input class="input__time" type="time" name="break_time[{{ $index }}][break_end]" value="{{ old("break_time.$index.break_end", \Carbon\Carbon::parse($break_time->break_end)->format('H:i')) }}">
                            </div>
                            <div class="form__error">
                                @error("break_time.{$index}.break_start")
                                    {{ $message }}
                                @enderror
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr class="table__row">
                    <th class="table__header">休憩{{ count($break_times) ? count($break_times) + 1 : '' }}</th>
                    <td class="table__detail">
                        <div class="detail__group">
                            <input class="input__time" type="time" name="break_time[{{ count($break_times) }}][break_start]" value="{{ old('break_time.'. count($break_times). '.break_start') }}">
                            <span>〜</span>
                            <input class="input__time" type="time" name="break_time[{{ count($break_times) }}][break_end]" value="{{ old('break_time.'. count($break_times). '.break_end') }}">
                        </div>
                        <div class="form__error">
                            @error("break_time." . count($break_times) . ".break_start")
                                {{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">備考</th>
                    <td class="table__detail">
                        <textarea class="textarea" name="reason">
                            {{ old('reason') }}
                        </textarea>
                        <div class="form__error">
                            @error('reason')
                                {{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="button">
            <button class="edit__button" type="submit">修正</button>
        </div>
    </form>
</div>
@endsection