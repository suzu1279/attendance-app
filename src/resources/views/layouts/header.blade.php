<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>模擬案件＿勤怠</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <h1 class="header-ttl">
            <a href="/">COACHTECH</a>
        </h1>
        <nav class="header-nav">
            <ul class="header-nav-list">
                @if (Auth::check())
                <li class="header-nav-item">
                    <a href="/attendance">勤怠</a>
                </li>
                <li class="header-nav-item">
                    <a href="/attendance/list">勤怠一覧</a>
                </li>
                <li class="header-nav-item">
                    <a href="/application/list">
                        <button class="button">申請</button>
                    </a>
                </li>
                <li class="header-nav-item">
                    <form class="form" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="button">ログアウト</button>
                    </form>
                </li>
                @endif
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>