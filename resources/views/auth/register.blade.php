@extends('layouts.app')
@section('seo')

@endsection
@section('title')
    Register
@endsection
@section('content')
    <div class="container-fluid" style="margin-top: 50px;">
{{--        <div class="card s12">--}}
            <div class="row">
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
                            {{ session('message') }}
                        </div>
                    @endif
                    <center>
                        <i class="large material-icons prefix " >person_add</i>
                    </center>
                    <h4 style="text-align: center;"><strong>Sign-up</strong></h4>
                    <form class="col s12" method="post" action="{{ route('register') }}" style="padding: 20px;">
                            @csrf
                            <div class="row">
                                <div class="input-field col s12 m6">
                                    <i class="material-icons prefix">person</i>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="validate">
                                    <label for="name">Fullname</label>
                                </div>

                                <div class="input-field col s12 m6">
                                    <i class="material-icons prefix">email</i>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="validate">
                                    <label for="email">Email</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">vpn_key</i>
                                    <input id="password" type="password" name="password"  required class="validate">
                                    <label for="password">Password</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">vpn_key</i>
                                    <input id="conf_password" type="password" name="confirm_password" required class="validate">
                                    <label for="conf_password">Confirm Password</label>
                                </div>
                            </div>

                            <button type="submit" class="waves-effect waves-light btn red darken-4 col s12 m12">Register</button>

                        </form>
                </div>
            </div>
{{--        </div>--}}
    </div>
@endsection
