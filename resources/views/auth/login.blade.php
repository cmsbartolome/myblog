@extends('layouts.app')
@section('seo')

@endsection
@section('title')
    Login
@endsection
@section('content')
    <style>
        /* label color */
        .input-field label {
            color: #000;
        }
        /* label focus color */
        .input-field input[type=text]:focus + label {
            color: #000;
        }
        /* label underline focus color */
        .input-field input[type=text]:focus {
            border-bottom: 1px solid #000;
            box-shadow: 0 1px 0 0 #000;
        }
        /* valid color */
        .input-field input[type=text].valid {
            border-bottom: 1px solid #000;
            box-shadow: 0 1px 0 0 #000;
        }
        /* invalid color */
        .input-field input[type=text].invalid {
            border-bottom: 1px solid #000;
            box-shadow: 0 1px 0 0 #000;
        }
        /* icon prefix focus color */
        .input-field .prefix.active {
            color: #000;
        }
    </style>

    <div class="container-fluid" style="margin-top: 50px;">
{{--        <div class="card s12 m6">--}}
            <div class="row" style="padding: 20px;">
                <div class="col s12 m6">

                </div>
                <div class="col s12 m6">
                    @if ($errors->any())
                        <div class="">
                            <ol>
                                @foreach ($errors->all() as $error)
                                    <li style="color: darkred; font-weight: 500;">{{ $error }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="">
                            {{ session('status') }}
                        </div>
                    @endif
                    <center>
                        <i class="large material-icons prefix " >account_circle</i>
                    </center>
                    <h4 style="text-align: center;"><strong>Sign-in</strong></h4>
                    <form class="col s12" method="post" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">email</i>
                                <input id="email" type="email" name="email" class="validate" value="{{ old('email') }}">
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">vpn_key</i>
                                <input id="password" type="password" name="password" class="@error('password') is-invalid @enderror">
                                <label for="password">Password</label>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col s12" style="margin-bottom: 15px;">
                                    <button type="submit" class="z-depth-3 waves-effect waves-light btn-large red darken-4 col s12"> Login <ion-icon name="log-in-sharp"></ion-icon></button>
                                </div>
                                <div class="col s12 m6" style="margin-bottom: 15px;">
                                    <a href="{{ route('google-redirect') }}" class="z-depth-3 waves-effect waves-light btn-large col s12" style="background: #C9574B;">Login with <ion-icon name="logo-google"></ion-icon></a>
                                </div>
                                <div class="col s12 m6" style="margin-bottom: 15px;">
                                    <a href="{{ route('facebook-redirect') }}" class="z-depth-3 waves-effect waves-light btn-large col s12" style="background: #425894">Login with <ion-icon name="logo-facebook"></ion-icon></a>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
{{--        </div>--}}
    </div>
@endsection
