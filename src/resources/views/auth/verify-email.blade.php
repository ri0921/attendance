@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
<div class="main">
    <div class="verify__message">
        <p>登録していただいたメールアドレスに認証メールを送付しました。</br>メール認証を完了してください。</p>
    </div>
    <div class="verify__button">
        <a class="verify__button-submit" href="https://mailtrap.io/inboxes">認証はこちらから</a>
    </div>
    <div class="resend-mail">
        <form method="post" action="/email/verification-notification">
            @csrf
            <button type="submit" class="resend-mail__link">
                認証メールを再送する
            </button>
        </form>
    </div>
</div>
@endsection
