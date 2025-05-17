@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="main">
    <h1 class="title">会員登録</h1>
    <div class="form">
        <form action="/register" method="POST">
            @csrf
            <div class="form__group">
                <label class="form__label" for="name">名前</label>
                <input class="form__input" type="text" name="name" value="{{ old('name') }}">
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label class="form__label" for="email">メールアドレス</label>
                <input class="form__input" type="text" name="email" value="{{ old('email') }}">
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label class="form__label" for="password">パスワード</label>
                <input class="form__input" type="password" name="password">
                <div class="form__error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label class="form__label" for="password_confirmation">パスワード確認</label>
                <input class="form__input" type="password" name="password_confirmation">
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">登録する</button>
            </div>
            <div class="login__link">
                <a class="login__link-text" href="/login">ログインはこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection
