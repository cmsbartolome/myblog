<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('seo')
    <title>MyBlog | @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @yield('css')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</head>
<body>
<style>
    body{
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    #subscribe_field {
        border: none;
        border-radius: 5px;
        padding: 5px 3px 5px 3px;
        background: #eeeeee;
        box-shadow: 0 1px 0 0 #eeeeee;
        font-size: 14px;
        /*margin: 20px 0;*/
        /*box-sizing: border-box;*/
    }
    #subscribe_field input[type=email]:focus {
        border-bottom: 1px #eeeeee;
    }
    .social_btn{
        list-style: none;
        color: #f1f1f1;
    }
    .social_btn > li {
        display: inline;
    }
    .loader {
        position: fixed;
        z-index: 99;
        top: 0;
        left: 0;
        background: #fff;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loader.hidden {
        -webkit-animation: fadeOut 1s;
        animation: fadeOut 1s;
        -webkit-animation-fill-mode: forwards;
        animation-fill-mode: forwards;
        z-index: -5;
    }

    @-webkit-keyframes fadeOut {
        100% {
            opacity: 0;
            visibility: hidden;
        }
    }

    @keyframes fadeOut {
        100% {
            opacity: 0;
            visibility: hidden;
        }
    }

    #footer{
        bottom: 0;
        margin: 0;
        height: 400px;
        color: #f1f1f1;
        background-image: url('{{url('storage/assets/3.jpg') }}');
        /*width: 100vh;*/
        /*height: 100vh;*/
        background-size: cover;
        background-repeat: no-repeat;
        width: 100%;
    }

    #footer > .container {
        padding-top: 20px;
    }

    #footer li {
        margin-top: 20px;
    }

    .sk-circle {
        margin: 100px auto;
        width: 40px;
        height: 40px;
        position: relative;
    }
    .sk-circle .sk-child {
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
    }
    .sk-circle .sk-child:before {
        content: '';
        display: block;
        margin: 0 auto;
        width: 15%;
        height: 15%;
        background-color: #333;
        border-radius: 100%;
        -webkit-animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
        animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
    }
    .sk-circle .sk-circle2 {
        -webkit-transform: rotate(30deg);
        -ms-transform: rotate(30deg);
        transform: rotate(30deg); }
    .sk-circle .sk-circle3 {
        -webkit-transform: rotate(60deg);
        -ms-transform: rotate(60deg);
        transform: rotate(60deg); }
    .sk-circle .sk-circle4 {
        -webkit-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        transform: rotate(90deg); }
    .sk-circle .sk-circle5 {
        -webkit-transform: rotate(120deg);
        -ms-transform: rotate(120deg);
        transform: rotate(120deg); }
    .sk-circle .sk-circle6 {
        -webkit-transform: rotate(150deg);
        -ms-transform: rotate(150deg);
        transform: rotate(150deg); }
    .sk-circle .sk-circle7 {
        -webkit-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg); }
    .sk-circle .sk-circle8 {
        -webkit-transform: rotate(210deg);
        -ms-transform: rotate(210deg);
        transform: rotate(210deg); }
    .sk-circle .sk-circle9 {
        -webkit-transform: rotate(240deg);
        -ms-transform: rotate(240deg);
        transform: rotate(240deg); }
    .sk-circle .sk-circle10 {
        -webkit-transform: rotate(270deg);
        -ms-transform: rotate(270deg);
        transform: rotate(270deg); }
    .sk-circle .sk-circle11 {
        -webkit-transform: rotate(300deg);
        -ms-transform: rotate(300deg);
        transform: rotate(300deg); }
    .sk-circle .sk-circle12 {
        -webkit-transform: rotate(330deg);
        -ms-transform: rotate(330deg);
        transform: rotate(330deg); }
    .sk-circle .sk-circle2:before {
        -webkit-animation-delay: -1.1s;
        animation-delay: -1.1s; }
    .sk-circle .sk-circle3:before {
        -webkit-animation-delay: -1s;
        animation-delay: -1s; }
    .sk-circle .sk-circle4:before {
        -webkit-animation-delay: -0.9s;
        animation-delay: -0.9s; }
    .sk-circle .sk-circle5:before {
        -webkit-animation-delay: -0.8s;
        animation-delay: -0.8s; }
    .sk-circle .sk-circle6:before {
        -webkit-animation-delay: -0.7s;
        animation-delay: -0.7s; }
    .sk-circle .sk-circle7:before {
        -webkit-animation-delay: -0.6s;
        animation-delay: -0.6s; }
    .sk-circle .sk-circle8:before {
        -webkit-animation-delay: -0.5s;
        animation-delay: -0.5s; }
    .sk-circle .sk-circle9:before {
        -webkit-animation-delay: -0.4s;
        animation-delay: -0.4s; }
    .sk-circle .sk-circle10:before {
        -webkit-animation-delay: -0.3s;
        animation-delay: -0.3s; }
    .sk-circle .sk-circle11:before {
        -webkit-animation-delay: -0.2s;
        animation-delay: -0.2s; }
    .sk-circle .sk-circle12:before {
        -webkit-animation-delay: -0.1s;
        animation-delay: -0.1s; }

    @-webkit-keyframes sk-circleBounceDelay {
        0%, 80%, 100% {
            -webkit-transform: scale(0);
            transform: scale(0);
        } 40% {
              -webkit-transform: scale(1);
              transform: scale(1);
          }
    }

    @keyframes sk-circleBounceDelay {
        0%, 80%, 100% {
            -webkit-transform: scale(0);
            transform: scale(0);
        } 40% {
              -webkit-transform: scale(1);
              transform: scale(1);
          }
    }
</style>
@include('layouts.header.header')

@auth
<ul id="slide-out" class="sidenav">
    <li><div class="user-view">
            <div class="background">
                <img src="images/office.jpg">
            </div>
            <a href="#user"><img class="circle" src="images/yuna.jpg"></a>
            <a href="#name"><span class="white-text name">{{auth()->user()->name}}</span></a>
            <a href="#email"><span class="white-text email">{{auth()->user()->email}}</span></a>
        </div></li>
    <li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>
    <li><a href="#!">Second Link</a></li>
    <li><div class="divider"></div></li>
    <li><a class="subheader">Subheader</a></li>
    <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
</ul>
@else
    <ul id="slide-out" class="sidenav" style="background: #161f29">
        <li class="white-text"><center><h5>My Blog</h5></center></li>
        <li class="white-text"><div class="divider"></div></li>
        <li><a href="{{ url('login') }}" class="waves-effect white-text"><i class="material-icons white-text">lock_open</i>Signin</a></li>
        <li><a href="{{ url('register') }}" class="waves-effect white-text"><i class="material-icons white-text">group_add</i>Signup</a></li>
{{--        <li class="white-text"><a href="#!">Second Link</a></li>--}}
{{--        <li class="white-text"><div class="divider"></div></li>--}}
{{--        <li class="white-text"><a class="subheader">Subheader</a></li>--}}
{{--        <li class="white-text"><a class="waves-effect" href="#!">Third Link With Waves</a></li>--}}
    </ul>
@endif

<!-- preloader -->
<div class="loader">
    <div class="preloader-wrapper big active">
        <div class="spinner-layer spinner-red">
            <div class="circle-clipper left">
                <div class="circle"></div>
            </div><div class="gap-patch">
                <div class="circle"></div>
            </div><div class="circle-clipper right">
                <div class="circle"></div>
            </div>
        </div>
    </div>
</div>

@yield('content')



@auth
<div id="modal1" class="modal" style="border-radius: 10px;">
    <div class="modal-content">
        <h4>Logout Confirmation</h4>
        <p>Are you sure you want to logout?</p>
    </div>
    <div class="modal-footer">
        <a href="{{ route('logout') }}" class="modal-close waves-effect waves-green white-text btn-flat red darken-4"
           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">Yes</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#!" class="modal-close waves-effect btn-flat right">No</a>
    </div>
</div>
@endauth

@yield('footer')
<script>
    //for date formatting
    let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

    $.ajaxSetup({
        headers:{
            "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")
        }
    });

    window.addEventListener("load", function () {
        const loader = document.querySelector(".loader");
        loader.className += "  hidden";
    });

    @auth
        $('.modal').modal();
    @endauth
    $('.sidenav').sidenav();
    $('.tooltipped').tooltip();
    $(window).scroll(function(){
        let $nav = $(".nav-wrapper");
        if($(window).scrollTop() > $(window).height()){
            $nav.removeClass('red');
            $nav.removeClass('darken-2');
            $nav.addClass('white');
            $('ul#nav-mobile > li > a > i').removeClass('white-text');
            $('ul#nav-mobile > li > a > i').addClass('black-text');
            $('.brand-logo').css('color', 'black');
        }
        else{
            $('.brand-logo').css('color', 'white');
            $nav.addClass('red');
            $nav.addClass('darken-2');
            $('ul#nav-mobile > li > a > i').addClass('white-text');
        }
    })
</script>
@yield('scripts')
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
