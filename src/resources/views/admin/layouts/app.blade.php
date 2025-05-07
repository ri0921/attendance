<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FleaMarket</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
@if (request()->is('admin/login'))
    <header class="header">
        <div class="header__inner">
            <h1 class="header__logo">
                <img src="{{ asset('logo.svg') }}" alt="logo" width="100%">
            </h1>
        </div>
    </header>
@else
    <header class="header">
        <div class="header__inner">
            <h1 class="header__logo">
                <a href="/">
                    <img src="{{ asset('logo.svg') }}" alt="logo" width="100%">
                </a>
            </h1>
            <form class="search-form" action="/search" method="get">
                @csrf
                <input class="search-form__input" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                <input type="hidden" name="tab" value="{{ request('tab') }}">
                <input type="submit" style="display:none;">
            </form>
            <nav class="header__nav">
                <ul class="header__nav-list">
                    @if (Auth::check())
                    <li>
                        <form action="/logout" method="post">
                            @csrf
                            <button class="nav__auth" type="submit">ログアウト</button>
                        </form>
                    </li>
                    @else
                    <li><a class="nav__link" href="/login">ログイン</a></li>
                    @endif
                    <li><a class="nav__link" href="/mypage">マイページ</a></li>
                    <li><button class="nav__button" onclick="location.href='/sell'">出品</button></li>
                </ul>
            </nav>
        </div>
    </header>
@endif

    <main>
        @yield('content')
    </main>
</body>
</html>