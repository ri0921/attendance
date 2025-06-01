@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/approve.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">勤怠詳細</h2>
    <form action="/stamp_correction_request/approve/{{ $correction_attendance->id }}" method="post">
        @csrf
        <div class="table-wrapper">
            <table class="table">
                <tr class="table__row">
                    <th class="table__header">名前</th>
                    <td class="table__detail">
                        {{ $correction_attendance->user->name }}
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">日付</th>
                    <td class="table__detail">
                        <div class="detail__group">
                            <div class="year">
                                {{ \Carbon\Carbon::parse($correction_attendance->attendance->date)->format('Y年')}}
                            </div>
                            <div class="date">
                                {{ \Carbon\Carbon::parse($correction_attendance->attendance->date)->format('m月d日')}}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">出勤・退勤</th>
                    <td class="table__detail">
                        <div class="detail__group">
                            {{ \Carbon\Carbon::parse($correction_attendance->clock_in)->format('H:i')}}
                            <span>〜</span>
                            {{ \Carbon\Carbon::parse($correction_attendance->clock_out)->format('H:i')}}
                        </div>
                    </td>
                </tr>
                @foreach ($correction_breaks as $index => $correction_break)
                    <tr class="table__row">
                        <th class="table__header">休憩{{ $index === 0 ? '' : $index + 1 }}</th>
                        <td class="table__detail">
                            <div class="detail__group">
                                {{ \Carbon\Carbon::parse($correction_break->break_start)->format('H:i')}}
                                <span>〜</span>
                                {{ \Carbon\Carbon::parse($correction_break->break_end)->format('H:i')}}
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr class="table__row">
                    <th class="table__header">備考</th>
                    <td class="table__detail">
                        {{ $correction_attendance->reason }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="button">
            <button class="approve__button" type="submit">承認</button>
        </div>
    </form>
</div>
@endsection