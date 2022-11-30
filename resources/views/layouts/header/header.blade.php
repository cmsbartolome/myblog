<div class="navbar-fixed">
<nav>
    <div class="nav-wrapper red darken-2">
        <div class="container">
            <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <a href="{{ url('/') }}" class="brand-logo">My Blog</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                    @auth
                        <li class="tooltipped" data-position="bottom" data-tooltip="Dashboard"><a href="{{ url('/dashboard') }}"><i class="material-icons white-text">home</i></a></li>
                        <li class="tooltipped" data-position="bottom" data-tooltip="My Articles"><a href="{{ route('articles') }}"><i class="material-icons white-text">insert_drive_file</i></a></li>
                        <li class="tooltipped" data-position="bottom" data-tooltip="My Profile"><a href="{{ route('user-profile') }}"><i class="material-icons white-text">account_circle</i></a></li>
                        <li class="tooltipped" data-position="bottom" data-tooltip="Settings"><a href="#"><i class="material-icons white-text">settings</i></a></li>
                        <li class="tooltipped" data-position="bottom" data-tooltip="Logout"><a class="modal-trigger" href="#modal1"><i class="material-icons white-text">lock_outline</i></a></li>
                    @else
                        <li class="tooltipped" data-position="bottom" data-tooltip="Login"><a href="{{ url('login') }}"><i class="material-icons white-text">lock_open</i></a></li>
                        <li class="tooltipped" data-position="bottom" data-tooltip="Register"><a href="{{ url('register') }}"><i class="material-icons white-text">group_add</i></a></li>
                    @endif

            </ul>
        </div>
    </div>
</nav>
</div>
