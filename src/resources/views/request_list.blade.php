@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request_list.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">申請一覧</h2>
    <div class="tabs">
        <a href="" class="tab selected">承認待ち</a>
        <a href="" class="tab">承認済み</a>
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
        <tr class="table__row">
            <td class="table__detail">承認待ち</td>
            <td class="table__detail">西伶奈</td>
            <td class="table__detail">2023/06/01</td>
            <td class="table__detail">遅延のため</td>
            <td class="table__detail">2023/06/02</td>
            <td class="table__detail"><a class="detail__link" href="/attendance/{id}/user">詳細</a></td>
        </tr>
        <tr class="table__row">
            <td class="table__detail">承認待ち</td>
            <td class="table__detail">西伶奈</td>
            <td class="table__detail">2023/06/01</td>
            <td class="table__detail">遅延のため</td>
            <td class="table__detail">2023/06/02</td>
            <td class="table__detail"><a class="detail__link" href="/attendance/{id}/user">詳細</a></td>
        </tr>
        <tr class="table__row">
            <td class="table__detail">承認待ち</td>
            <td class="table__detail">西伶奈</td>
            <td class="table__detail">2023/06/01</td>
            <td class="table__detail">遅延のため</td>
            <td class="table__detail">2023/06/02</td>
            <td class="table__detail"><a class="detail__link" href="/attendance/{id}/user">詳細</a></td>
        </tr>
    </table>
</div>
@endsection