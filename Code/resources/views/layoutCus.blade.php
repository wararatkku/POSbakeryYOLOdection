<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('cssfile/style.css') }}">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>@yield('title')</title>
</head>

<body class="home-page">
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="https://img.freepik.com/free-vector/illustration-bakery-house-stamp-banner_53876-6838.jpg?w=740&t=st=1703609652~exp=1703610252~hmac=2bfe80cbf9a43f5da83d1f93301cfba3e1241071235a621d2af76dfe6351d32e"
                        alt="logo">
                </span>

                <div class="text header-text">
                    {{-- <span class="name">BakeryProject</span> --}}
                    @if (auth()->check())
                        <p class="text-username" style="font-size: 14px;margin-top:10px">สวัสดีคุณ, {{ auth()->user()->name }}</p>
                    @endif
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="/">
                            <i class="bx bx-home icon"></i>
                            <span class="text nav-text">หน้าหลัก</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/Detect">
                            <i class='bx bx-camera icon'></i>
                            <span class="text nav-text">สแกนเบเกอรี่</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/Bakery">
                            <i class='bx bx-baguette icon'></i>
                            <span class="text nav-text">รายละเอียดเบเกอรี่</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/History">
                            <i class='bx bx-history icon'></i>
                            <span class="text nav-text">ประวัติการชำระเงิน</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <li class="">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">ออกจากระบบ</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </div>
        </div>

    </nav>


    <section class="content @yield('contentClass')">
        @yield('contents')
    </section>

    <section>
        @yield('scripts')
    </section>

    <script src="js/script.js"></script>

</body>

</html>
