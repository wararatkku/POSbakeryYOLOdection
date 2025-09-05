@extends('layoutCus')
@section('title')
    Home
@endsection
@section('contents')
    <p class="name-user"
        style="text-align: right; font-size: 18px; margin-right:15px; font-weight: bold; color: #000000; padding: 10px; border-radius: 5px;">
        สวัสดีคุณ {{ Auth::user()->name }}
    </p>

    <a href="/Detect" class="center">
        <div class="article-card">
            <div class="content-card">
                <p class="title">สแกนเบเกอรี่</p>
            </div>
            <img src="https://img.freepik.com/free-vector/pattern-with-hand-drawn-cameras_23-2147513745.jpg?w=740&t=st=1704652682~exp=1704653282~hmac=507180a64e2da2dedfba53d75f5d37506b4b6a6012c698259627d768779f7557"
                alt="article-cover" />
        </div>
    </a>
    <a href="/Bakery" class="center">
        <div class="article-card">
            <div class="content-card">
                <p class="title">เบเกอรี่</p>
            </div>
            <img src="https://img.freepik.com/free-vector/hand-drawn-pattern-background_23-2150829915.jpg?w=996&t=st=1704653230~exp=1704653830~hmac=9145b820cc478e94faa8d40eb048f0689f9e9148cebc417e9cf37a50264e7d86"
                alt="article-cover" />
        </div>
    </a>
    <a href="/History" class="center">
        <div class="article-card">
            <div class="content-card">
                <p class="title">ประวัติการชำระเงิน</p>
            </div>
            <img src="https://img.freepik.com/free-vector/isometric-shop-receipt-paper-payment-bill_107791-292.jpg?w=1380&t=st=1704653478~exp=1704654078~hmac=ffd7e30544e540d0e8222c9fa0d6c365cca799c65f401e419e42ed0c6319f4fb"
                alt="article-cover" />
        </div>
    </a>
@endsection
