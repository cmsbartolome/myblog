@extends('layouts.app')
@section('seo')

@endsection
@section('title')
    Profile
@endsection
@section('content')
    <style>

    </style>
    <div class="container">
        <div class="row">
            <div class="col m10">
                <h4>My Profile</h4>
            </div>
            <div class="col m2">

            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col s12 m4">
                @php
                    $mediaName = hash('sha256', auth()->user()->id);
                    $imgpath = $user->getFirstMediaUrl($mediaName, '') ? $user->getFirstMediaUrl($mediaName, '') :
                    ((isset(auth()->user()->avatar) ? auth()->user()->avatar : 'https://picsum.photos/200/200?random=3'));
                @endphp
                <img src="{{$imgpath}}" alt="{{$imgpath}}" class="circle" id="preview-img" width="200" height="200">
                @if(!isset(auth()->user()->avatar))
                    <form class="col s12" method="post" action="{{ route('update-profile') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="image" id="avatar" accept="image/jpeg, image/png" onChange="readURL(this.id,'preview-img','{{$imgpath}}',200,200)">
                        <div class="row">
                            <button type="submit" class="waves-effect waves-light btn red darken-4 small">Upload</button>
                        </div>
                    </form>
                @endif
            </div>
            <div class="col s12 m8">
                <h5>Personal Information</h5>
                @if ($errors->any())
                    <div class="">
                        <ol>
                            @foreach ($errors->all() as $error)
                                <li style="color: darkred; font-weight: 500;">{{ $error }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
                @if (session('success') || session('failed'))
                    <div class="row">
                        <div class="col s12 m12">
                            <span style="color: {{ session('success') ? '#37FF27' : 'darkred' }}; font-weight: 500;">{{ session('success') ?? session('failed') }}</span>
                        </div>
                    </div>
                @endif
                <div>
                    <form class="col s12" method="post" action="{{ route('update-profile') }}">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" type="email" name="email" value="{{ auth()->user()->email ?? '' }}" readonly>
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" type="text" name="name" max="100" onkeydown="valText(this.id)" onkeyup="valText(this.id)" class="validate" value="{{ auth()->user()->name ?? '' }}">
                                <label for="name">Fullname</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="birthday" type="date" name="birthday" class="validate" value="{{ auth()->user()->birthday ?? '' }}">
                                <label for="birthday">Birthday</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="mobile" type="text" onkeydown="valNum(this.id)" maxlength="20" onkeyup="valNum(this.id)" name="mobile" class="validate" value="{{ auth()->user()->mobile ?? '' }}">
                                <label for="mobile">Mobile No.</label>
                            </div>
                        </div>
                        <div class="row">
                            <button type="submit" class="waves-effect waves-light btn red darken-4 small">Update</button>
                        </div>
                    </form>
                </div>
                <div>
                <h5>Security Information</h5>
                    <form class="col s12" method="post" action="{{ route('update-profile') }}">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="old_password" type="password" name="old_password"  required class="validate">
                                <label for="password">Old Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" type="password" name="password"  required class="validate">
                                <label for="password">New Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="conf_password" type="password" name="confirm_password" required class="validate">
                                <label for="conf_password">Confirm Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <button type="submit" class="waves-effect waves-light btn red darken-4 small">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <br><br>
        <br><br>
    </div>
@endsection
@section('scripts')
<script>
    function valNum(e){
        let t=document.getElementById(e),a=/[^0-9]/gi;t.value.search(a)>-1&&(t.value=t.value.replace(a,""))
    }

    function valText(e){
        let q=document.getElementById(e),b=/[^a-zA-Z ]/gi;q.value.search(b)>-1&&(q.value=q.value.replace(b,""))
    }

    function cancelfile(input,displayTo,baseurl,height,width,btnCancel) {
        $('#'+btnCancel).hide();
        $('#'+input).val("");
        $('#'+displayTo).attr('src', baseurl);
        if(height != null && width !=null) {
            $('#' + displayTo).attr('height', height);
            $('#' + displayTo).attr('width', width);
        }
    }
    function readURL(input,displayTo,baseurl,width = null,height = null) {
        let file = document.getElementById(input);
        if (document.getElementById(input).files.length == 0) {
            $('#' + displayTo).attr('src', baseurl);
        }
        else {
            if (file.files && file.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#' + displayTo).attr('src', e.target.result);
                    if (height != null && width != null) {
                        $('#' + displayTo).attr('height', height);
                        $('#' + displayTo).attr('width', width);
                    }
                }
                reader.readAsDataURL(file.files[0]);
            }
            else {
                $('#' + displayTo).attr('src', baseurl);
            }
        }
    }

</script>
@endsection
