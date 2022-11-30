@extends('layouts.app')
@section('seo')

@endsection
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
@endsection
@section('title')
Home
@endsection
@section('content')
<style>
    body {
        margin: 0px;
    }
    .slick-prev, .slick-next{
        width: 40px;
        height: 40px;
    }
    .slick-prev:before, .slick-next:before{
        color: #000000;
    }
    .slick-prev:hover,
    .slick-prev:focus,
    .slick-next:hover,
    .slick-next:focus
    {
        color: #161f29;
    }
    .overlap_searchbar {
        position: absolute; /* Reposition logo from the natural layout */
        left: 0px;
        top: 300px; /*350px*/
        width: 100%;
        height: 200px;
        z-index: 2;
    }
    #search_field {
        border: none;
        border-radius: 5px;
        padding: 15px 3px 15px 3px;
        background: #eeeeee;
        box-shadow: 0 1px 0 0 #eeeeee;
        font-size: 30px;
        /*margin: 20px 0;*/
        /*box-sizing: border-box;*/
        outline: none;
    }
    #search_field input[type=search]:focus {
        border-bottom: 1px #eeeeee;
    }
    .sbtn {
        position: absolute;
        border-radius: 10px;
        right: 0px;
        z-index: 2;
        border: none;
        top: 30px;
        height: 55px;
        cursor: pointer;
        color: white;
        background-color: #1e90ff;
        transform: translateX(2px);
    }
    .custom-container{
        margin:0 auto;
        max-width:1460px;
        width:90%
    }
    @media only screen and (min-width: 601px){
        .custom-container{
            width:95%
        }
    }
    @media only screen and (min-width: 993px){
        .custom-container{width:90%}
    }
</style>
<div class="slider">
    <ul class="slides">
        <li>
            <img src="https://picsum.photos/1690/1027?random=1"> <!-- random image -->
            <div class="caption center-align">
                <h3>This is our big Tagline!</h3>
                <h5 class="light grey-text text-lighten-3">Here's our small slogan.</h5>
            </div>
        </li>
        <li>
            <img src="https://picsum.photos/1690/1027?random=2"> <!-- random image -->
            <div class="caption left-align">
                <h3>Left Aligned Caption</h3>
                <h5 class="light grey-text text-lighten-3">Here's our small slogan.</h5>
            </div>
        </li>
        <li>
            <img src="https://picsum.photos/1690/1027?random=3"> <!-- random image -->
            <div class="caption right-align">
                <h3>Right Aligned Caption</h3>
                <h5 class="light grey-text text-lighten-3">Here's our small slogan.</h5>
            </div>
        </li>
        <li>
            <img src="https://picsum.photos/1690/1027?random=4"> <!-- random image -->
            <div class="caption center-align">
                <h3>This is our big Tagline!</h3>
                <h5 class="light grey-text text-lighten-3">Here's our small slogan.</h5>
            </div>
        </li>
    </ul>
</div>
<div class="overlap_searchbar">
    <div class="custom-container">
        <div class="card z-depth-3" style="border-radius: 20px;">
            <div class="card-content">
                <div class="col s12 m12" >
                    <input type="search" id="search_field" name="search_article" placeholder="Search..">
                </div>
                <div class="col s12 m12">
                    <h4><center>Categories</center></h4>
                    <div class="categories">
                        @foreach($categories as $category)
                            <div style="margin: 5px 10px;">
                                <div class="card" style="border-radius: 10px;">
                                    <div class="card-content" style="min-height: 130px;">
                                        <span class="card-title">{{ $category->title ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section white" style="margin-top: 200px">
    <div class="row container">
        <h2 class="header">Top Blogs</h2>
        <p class="animate__animated animate__bounce grey-text text-darken-3 lighten-3">Here is the list blogs that most people view.</p>
    </div>
</div>
<div class="container">
    <div class="row">
        @foreach($topArticles as $key => $article)
            <div class="col s12 m4">
                <div class="card hoverable" style="min-height: 320px; border-radius: 20px;">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="materialboxed activator" src="https://picsum.photos/150/80?random={{ $key}}">
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">{{ $article->title ?? '' }}<i class="material-icons right">more_vert</i></span>
                        <p><a href="{{route('view-article', ['title'=>$article->title, 'id'=>$article->id])}}" class="btn waves-effect waves-light red pulse">View Article</a></p>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{ $article->title ?? '' }}<i class="material-icons right">close</i></span>
{{--                        <p>{{  Str::limit($article->short_description, 50) }}</p>--}}
                    </div>
                </div>
            </div>
{{--            <div class="col s12 m4">--}}
{{--                <div class="card hoverable" style="min-height: 380px; border-radius: 20px;">--}}
{{--                    <div class="card-image">--}}
{{--                        <img src="https://picsum.photos/150/80?random={{ $key }}">--}}
{{--                        <span class="card-title">{{ $article->title ?? '' }}</span>--}}
{{--                        <a class="btn-floating halfway-fab waves-effect waves-light red pulse"><i class="material-icons">visibility</i></a>--}}
{{--                    </div>--}}
{{--                    <div class="card-content">--}}
{{--                        <p></p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        @endforeach
    </div>
</div>
<div class="parallax-container">
    <div class="parallax"><img src="https://picsum.photos/1690/1127?random=3"></div>
</div>
<div class="container">

</div>
<br>
<div class="section white" >
    <div class="row container">
        <h2 class="header">New Blogs</h2>
        <p class="grey-text text-darken-3 lighten-3">Here is the list blogs that most people view.</p>
    </div>
</div>
<div class="container">
    <div class="row">
        @foreach($newArticles as $key => $newarticle)
            <div class="col s12 m4">
                <div class="card hoverable" style="min-height: 320px; border-radius: 20px;">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="materialboxed activator" src="https://picsum.photos/150/80?random={{ $key}}">
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">{{ $newarticle->title ?? '' }}<i class="material-icons right">more_vert</i></span>
                        <p><a href="{{route('view-article', ['title'=>$newarticle->title, 'id'=>$newarticle->id])}}" class="btn waves-effect waves-light red pulse">View Article</a></p>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{ $newarticle->title ?? '' }}<i class="material-icons right">close</i></span>
                        {{--                        <p>{{  Str::limit($article->short_description, 50) }}</p>--}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<br>
<div class="container">

</div>
@endsection
@section('footer')
    @include('layouts.footer.footer')
@endsection
@section('scripts')
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.carousel').carousel();
            $('.parallax').parallax();
            $('.slider').slider({
                height: 450,
                indicators: false,
                duration: 1000
            });
            $('select').formSelect();
            $('.materialboxed').materialbox();
        });

        $('.categories').slick({
            dots: false,
            arrows: true,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: false,
                        arrows: true,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    </script>
@endsection
