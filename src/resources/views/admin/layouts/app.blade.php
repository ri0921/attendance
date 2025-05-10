<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/common.css') }}">
    @yield('css')
</head>

<body>
@if (request()->is('login'))
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
                <img src="{{ asset('logo.svg') }}" alt="logo" width="100%">
            </h1>
            <nav>
                <ul class="header__nav">
                    <li>
                        <a class="nav__link" href="/attendance">勤怠一覧</a>
                    </li>
                    <li>
                        <a class="nav__link" href="/attendance/list">スタッフ一覧</a>
                    </li>
                    <li>
                        <a class="nav__link" href="/stamp_correction_request/list">申請一覧</a>
                    </li>
                    <li>
                        <form action="/logout" method="post">
                            @csrf
                            <button class="logout__button" type="submit">ログアウト</button>
                        </form>
                    </li>
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