@extends('layouts.app')

@section('navItems')

<li><a href="{{ url('/') }}">Home</a></li>
<li><a href="{{ url('/shop') }}">Shop</a></li>
<li><a href="{{ url('/about') }}" class="active">About Us</a></li>
<li><a href="{{ url('/contact') }}">Contact</a></li>

@endsection

@section('content')

<!-- about wrapper start -->
<div class="about-us-wrapper pt-60 pb-40">
    <div class="container">
        <div class="row">
            <!-- About Text Start -->
            <div class="col-lg-6 order-last order-lg-first">
                <div class="about-text-wrap">
                    <h2><span>Provide Best</span>Product For You</h2>
                    <p>We provide the best Beard oile all over the world. We are the worldd best store in indi for Beard Oil. You can buy our product without any hegitation because they truste us and buy our product without any hagitation because they belive and always happy buy our product.</p>
                    <p>Some of our customer say’s that they trust us and buy our product without any hagitation because they belive us and always happy to buy our product.</p>
                    <p>We provide the beshat they trusted us and buy our product without any hagitation because they belive us and always happy to buy.</p>
                </div>
            </div>
            <!-- About Text End -->
            <!-- About Image Start -->
            <div class="col-lg-6 col-md-10">
                <div class="about-image-wrap">
                    <img class="img-full" src="{{ url("/assets/images/product/large-size/13.jpg") }}" alt="About Us" />
                </div>
            </div>
            <!-- About Image End -->
        </div>
    </div>
</div>
<!-- about wrapper end -->
<!-- Begin Counterup Area -->
<div class="counterup-area">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-lg-3 col-md-6">
                <!-- Begin Limupa Counter Area -->
                <div class="limupa-counter white-smoke-bg">
                    <div class="container">
                        <div class="counter-img">
                            <img src="{{ url("/assets/images/about-us/icon/1.png") }}" alt="">
                        </div>
                        <div class="counter-info">
                            <div class="counter-number">
                                <h3 class="counter">2169</h3>
                            </div>
                            <div class="counter-text">
                                <span>HAPPY CUSTOMERS</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- limupa Counter Area End Here -->
            </div>
            <div class="col-lg-3 col-md-6">
                <!-- Begin limupa Counter Area -->
                <div class="limupa-counter gray-bg">
                    <div class="counter-img">
                        <img src="{{ url("/assets/images/about-us/icon/2.png") }}" alt="">
                    </div>
                    <div class="counter-info">
                        <div class="counter-number">
                            <h3 class="counter">869</h3>
                        </div>
                        <div class="counter-text">
                            <span>AWARDS WINNED</span>
                        </div>
                    </div>
                </div>
                <!-- limupa Counter Area End Here -->
            </div>
            <div class="col-lg-3 col-md-6">
                <!-- Begin limupa Counter Area -->
                <div class="limupa-counter white-smoke-bg">
                    <div class="counter-img">
                        <img src="{{ url("/assets/images/about-us/icon/3.png") }}" alt="">
                    </div>
                    <div class="counter-info">
                        <div class="counter-number">
                            <h3 class="counter">689</h3>
                        </div>
                        <div class="counter-text">
                            <span>HOURS WORKED</span>
                        </div>
                    </div>
                </div>
                <!-- limupa Counter Area End Here -->
            </div>
            <div class="col-lg-3 col-md-6">
                <!-- Begin limupa Counter Area -->
                <div class="limupa-counter gray-bg">
                    <div class="counter-img">
                        <img src="{{ url("/assets/images/about-us/icon/4.png") }}" alt="">
                    </div>
                    <div class="counter-info">
                        <div class="counter-number">
                            <h3 class="counter">2169</h3>
                        </div>
                        <div class="counter-text">
                            <span>COMPLETE PROJECTS</span>
                        </div>
                    </div>
                </div>
                <!-- limupa Counter Area End Here -->
            </div>
        </div>
    </div>
</div>
<!-- Counterup Area End Here -->

@endsection