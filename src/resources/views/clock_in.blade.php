@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/clock_in.css') }}">
@endsection

@section('content')
<div class="main">
    <div class="work__status">
        出勤中
    </div>
    <div class="date">
        2025年5月10日(土)
    </div>
    <div class="time">
        08:00
    </div>
    <div class="time-log__button">
        <div class="button attendance">退勤</div>
        <div class="button break">休憩入</div>
    </div>
</div>
@endsection