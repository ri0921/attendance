@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/approve.css') }}">
@endsection

@section('content')
<div class="main">
    <h2 class="title">勤怠詳細</h2>
    <form action="" method="post">
        @csrf
        <div class="table-wrapper">
            <table class="table">
                <tr class="table__row">
                    <th class="table__header">名前</th>
                    <td class="table__detail">西　伶奈</td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">日付</th>
                    <td class="table__detail">
                        <div class="detail__group">
                            <div class="year">2023年</div>
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
        <div class="button">
            <button class="approve__button" type="submit">承認</button>
        </div>
    </form>
</div>
@endsection