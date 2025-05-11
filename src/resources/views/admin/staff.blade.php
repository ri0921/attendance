@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">スタッフ一覧</h2>
    <table class="table">
        <tr class="table__row">
            <th class="table__header">名前</th>
            <th class="table__header">メールアドレス</th>
            <th class="table__header">月次勤怠</th>
        </tr>
        <tr class="table__row">
            <td class="table__detail">西 伶奈</td>
            <td class="table__detail">reina.n@coachtech.com</td>
            <td class="table__detail"><a class="detail__link" href="/admin/attendance/staff/{id}">詳細</a></td>
        </tr>
        <tr class="table__row">
            <td class="table__detail">西 伶奈</td>
            <td class="table__detail">reina.n@coachtech.com</td>
            <td class="table__detail"><a class="detail__link" href="/admin/attendance/staff/{id}">詳細</a></td>
        </tr>
        <tr class="table__row">
            <td class="table__detail">西 伶奈</td>
            <td class="table__detail">reina.n@coachtech.com</td>
            <td class="table__detail"><a class="detail__link" href="/admin/attendance/staff/{id}">詳細</a></td>
        </tr>
    </table>
</div>
@endsection