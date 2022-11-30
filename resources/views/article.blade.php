@extends('layouts.app')
@section('seo')
    @php
        $keywords = json_decode($article->keywords);
    @endphp
    <meta description="" >
@endsection
@section('css')
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code table lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
        });
    </script>
@endsection
@section('title')
    {{ $artticle->title ?? '' }}
@endsection
@section('content')
<style>
.is_like{
    color: #D32F2F;
}
</style>
<div class="progress">
    <div class="indeterminate"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col s12 m8"> <h1>{{ $article->title ?? '' }}</h1></div>
        <div class="col s12 m4"><h5>Total post like: {{ $article->postTotalLikes() }}</h5></div>
    </div>
    {!! $article->description !!}
    <br/>
    <hr>
    <div class="row">
        <div class="col m8">
            <h5>Add a comment</h5>
        </div>
    </div>

    <form action="#" method="POST" id="comment_form">
        <input type="hidden" id="_id" value="{{$article->id ?? ''}}">
        <div class="row">
            <textarea id="description"></textarea>
        </div>
        <div class="row">
        @auth
            <button class="waves-effect waves-light btn red darken-4" id="cmt_btn" >Submit</button>
        @else
            <a href="{{ route('login', ['prev_url'=>request()->url()]) }}" class="waves-effect waves-light btn red darken-4" >Please Login to submit a comment</a>
        @endauth
        </div>
    </form>
    <br/>
    <div id="comments">
        <ul class="collection" id="load-data2">
            @foreach($article->comments as $key => $comment)
                @php
                    $avatar = isset($comment->user->avatar) ? optional($comment->user)->avatar : "https://picsum.photos/150/200?random=3"
                @endphp
                <li class="collection-item avatar comment-item" id="li-{{$comment->id ?? '' }}" style="margin-top: 10px;">
                    <img src="{{ $avatar }}" alt="{{ $avatar }}" referrerpolicy="no-referrer" class="circle">
                    <span class="title"><b>{{ optional($comment->user)->name ?? '' }}</b></span>
                    @auth
                        <a href="#!" class="secondary-content">
                            <ul>
                            @if($comment->isLike($comment->id, $article->id) == true)
                               <li class="black-text"> <span class="material-icons is_like" data-cmid="{{ $comment->id ?? '' }}" data-val="false">thumb_up</span> </li>
                            @else
                                <li> <span class="material-icons is_like" data-cmid="{{ $comment->id ?? '' }}" data-val="true">thumb_down</span> </li>
                            @endif
                            <li><span class="black-text" style="margin-top: 0px;">{{ $comment->commentTotalLikes($comment->id) }}</span></li>
                            </ul>
                        </a>
                    @endauth
                    <br/>
                    <div class="row" style="margin-top: 10px;">
                        {!! $comment->comment !!}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
@section('footer')
    @include('layouts.footer.footer')
@endsection
@section('scripts')
<script>
    $('.progress').hide();

    tinymce.init({
        selector: 'textarea#description', // Replace this CSS selector to match the placeholder element for TinyMCE
        plugins: 'code table lists',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
    });

    $('#comment_form').on('submit', function (e) {
        e.preventDefault();
        $('.progress').fadeIn();
        $('#cmt_btn').prop('disabled',  true);

        let comment = tinymce.get('description').getContent();
        let post_id = $('#_id').val();

        $.ajax({
            url: "{{ route('store-comment') }}",
            type: "POST",
            data:{comment:comment,post:post_id},
            dataType: 'json',
            success: function(result){
                if(result.success == '1'){
                    M.toast({html: result.message})
                    $('.progress').fadeOut();
                    tinymce.activeEditor.setContent('');
                    $("#cmt_btn").html('Submit');
                    $('#cmt_btn').prop('disabled',  false);

                    let data = JSON.parse(result.data);
                    let pic = data.avatar !== "" ? data.avatar : 'https://picsum.photos/150/200?random=3';
                    let isLike =  data.like == true ?
                      ' <li class="black-text"><span class="material-icons is_like" data-cmid="'+data.id+'" data-val="false">thumb_up</span></li>' :
                        ' <li class="black-text"><span class="material-icons is_like" data-cmid="'+data.id+'" data-val="true">thumb_down</span></li>';

                    isLike + '<li><span class="black-text" style="margin-top: 0px;">'+data.totalLikes+'</span></li>';


                    $('#load-data2').prepend('<li class="collection-item avatar comment-item" id="li-'+data.id+'" style="margin-top: 10px;">' +
                        '<img src="'+pic+'" alt="'+pic+'" referrerpolicy="no-referrer" class="circle">' +
                        '<span class="title"><b>' + data.name + '</b></span>' +
                        '<a href="#!" class="secondary-content"><ul>'+ isLike + '</ul></a>'+
                        '<br/>'+
                        '<div class="row" style="margin-top: 10px;">'+
                            '<p>'+data.comment+'</p>'+
                        '</div>'+
                        '</li>');
                } else {
                    M.toast({html: result.message})
                    $('.progress').fadeOut();
                    $("#cmt_btn").html('Submit');
                    $('#cmt_btn').prop('disabled',  false);
                }
            }
        });
    });

    $(document).on('click', '.is_like', function () {
        let is_like = $(this).data("val");
        let cmid = $(this).data("cmid");
        let post_id = $('#_id').val();
        let action = (is_like == true) ?  "like":"unlike" ;
        alert(is_like == true);

        $.ajax({
            url: "{{route('like-comment')}}",
            type: "POST",
            data: {cmt_id:cmid, p_id:post_id, action:action},
            success: function(result){
                if(result.success == '1'){
                    // M.toast({html: result.data});
                    console.log(result.data);

                    let data = JSON.parse(result.data);
                    let pic = data.avatar !== '' ? data.avatar : 'https://picsum.photos/150/200?random=3';
                    let isLike =  data.like == true ?
                        ' <li class="black-text"><span class="material-icons is_like" data-cmid="'+data.id+'" data-val="false">thumb_up</span></li>' :
                        ' <li class="black-text"><span class="material-icons is_like" data-cmid="'+data.id+'" data-val="true">thumb_down</span></li>';

                    $('#li-'+data.id).html('<img src="'+pic+'" alt="'+pic+'" referrerpolicy="no-referrer" class="circle">' +
                        '<span class="title"><b>' + data.name + '</b></span>' +
                        '<a href="#!" class="secondary-content"><ul>'+ isLike + '<li><span class="black-text" style="margin-top: 0px;">'+data.totalLikes+'</span></li></ul></a>'+
                        '<br/>'+
                        '<div class="row" style="margin-top: 10px;">'+
                            '<p>'+data.comment+'</p>'+
                        '</div>');

                } else {
                    M.toast({html: result.message})
                }
            }
        });
    });
</script>
@endsection
