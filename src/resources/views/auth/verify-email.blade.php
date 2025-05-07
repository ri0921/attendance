@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="main">
    <div class="verify__message">
        <p>登録していただいたメールアドレスに認証メールを送付しました。</br>メール認証を完了してください。</p>
    </div>
    <div class="verify__button">
        <button class="verify__button-submit">認証はこちらから</button>
    </div>
    <div class="resend-mail">
        <form method="POST" action="/email/verification-notification">
            @csrf
            <button type="submit" class="resend-mail__link">
                認証メールを再送する
            </button>
        </form>
    </div>
</div>
@endsection
