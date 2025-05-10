@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">2023年6月1日の勤怠</h2>
    <div class="date-selector">
        <a href="" class="arrow"><img class="arrow__image" src="{{ asset('img/left.png') }}">前日</a>
        <div class="date"><img class="calendar__image" src="{{ asset('img/calendar.png') }}">2023/06/01</div>
        <a href="" class="arrow">翌日<img class="arrow__image" src="{{ asset('img/right.png') }}"></a>
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
        <tr class="table__row">
            <td class="table__detail">西 伶奈</td>
            <td class="table__detail">09:00</td>
            <td class="table__detail">18:00</td>
            <td class="table__detail">1:00</td>
            <td class="table__detail">8:00</td>
            <td class="table__detail"><a class="detail__link" href="/attendance/{id}">詳細</a></td>
        </tr>
        <tr class="table__row">
            <td class="table__detail">西 伶奈</td>
            <td class="table__detail">09:00</td>
            <td class="table__detail">18:00</td>
            <td class="table__detail">1:00</td>
            <td class="table__detail">8:00</td>
            <td class="table__detail"><a class="detail__link" href="/attendance/{id}">詳細</a></td>
        </tr>
        <tr class="table__row">
            <td class="table__detail">西 伶奈</td>
            <td class="table__detail">09:00</td>
            <td class="table__detail">18:00</td>
            <td class="table__detail">1:00</td>
            <td class="table__detail">8:00</td>
            <td class="table__detail"><a class="detail__link" href="/attendance/{id}">詳細</a></td>
        </tr>
    </table>
</div>
@endsection