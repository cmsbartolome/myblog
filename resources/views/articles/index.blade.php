@extends('layouts.app')
@section('css')

@endsection
@section('title')
   My Articles
@endsection
@section('content')
   <style>
      .view_selector > li {
         display: inline;
      }
      .act {
         background: #D32F2F;
         border-radius: 50%;
         padding: 10px;
         color: #ffffff;
      }
   </style>
<div class="container">
   <div class="row">
      <div class="col m10">
         <h4>My Articles</h4>
         <a href="{{ route('create-article') }}" class="waves-effect waves-light btn red left">Create Article</a>
      </div>
      <div class="col m2">
         <ul class="view_selector right-align">
            <li><i class="tooltipped material-icons selector act" data-val="table" data-position="bottom" data-tooltip="Table view">border_all</i></li>
            <li><i class="tooltipped material-icons selector" data-val="grid" data-position="bottom" data-tooltip="Grid view">grid_on</i></li>
            <li><i class="tooltipped material-icons selector" data-val="list" data-position="bottom" data-tooltip="List view">format_list_bulleted</i></li>
         </ul>
      </div>
   </div>
   <div id="table">
      <table class="responsive highlight striped">
         <thead>
            <tr>
               <th>Article Name</th>
               <th>Created</th>
               <th>Favorite</th>
               <th>Views</th>
               <th>Active</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            @forelse($articles as $article)
               <tr id="tr_{{$article->id ?? ''}}">
                  <td>{{ucwords($article->title) ?? ''}}</td>
                  <td>
                     {{Carbon\Carbon::parse($article->created_at)->isoFormat('dddd, MMMM D, Y')}}
{{--                     {{$article->created_at->diffForHumans()}}--}}
                  </td>
                  <td>
                     @if($article->is_favorite == 1) <span class="material-icons is_favorite" data-id="{{ $article->id ?? ''}}" data-val="0">star</span> @else <span class="material-icons is_favorite" data-id="{{ $article->id ?? ''}}" data-val="1">star_border</span> @endif
                  </td>
                  <td>
                     {{ (int)$article->views }}
                  </td>
                  <td>
                     <label class="tooltipped" data-position="left" data-tooltip="{{ $article->active == 1 ? 'Set in-active' : 'Set active' }}">
                        <input type="checkbox" class="is_active" data-id="{{ $article->id ?? ''}}" {{ $article->active == 1 ? 'checked="checked"' : '' }}  />
                        <span></span>
                     </label>
                  </td>
                  <td>
                     <ul class="view_selector">
                        <li class="tooltipped" data-position="left" data-tooltip="Edit this article"> <a class="dark" href="{{ route('edit-article', ['id'=>$article->id ?? '']) }}"><i class="edit material-icons">edit</i></a> </li>
                        <li class="tooltipped" data-position="left" data-tooltip="Delete this article"> <i class="material-icons delete" data-id="{{ $article->id ?? ''}}" data-title="{{$article->title ?? ''}}">delete</i></li>
                     </ul>
                  </td>
               </tr>
            @empty
               <tr>
                  <td colspan="100%"><center>No Records available</center></td>
               </tr>
            @endforelse
         </tbody>
      </table>
      <center>

         @if (isset($articles))
            @if ($articles->lastPage() > 1)
               <div class="container" >
                  <ul class="pagination">
                     @if($articles->currentPage() != 1)
                        <li class="{{ ($articles->currentPage() == 1) ? 'disabled' : 'waves-effect' }}">
                           <a href="{{ $articles->url(1) }}">
                              <i class="material-icons">chevron_left</i>
                           </a>
                        </li>
                     @endif
                     @for ($i = 1; $i <= $articles->lastPage(); $i++)
                        <li class="waves-effect {{ ($articles->currentPage() == $i) ? 'active red' : '' }}">
                           <a href="{{ $articles->url($i) }}" >{{ $i }}</a>
                        </li>
                     @endfor
                     @if($articles->currentPage() != $articles->lastPage())
                        <li class="waves-effect {{ ($articles->currentPage() == $articles->lastPage()) ? 'disabled' : '' }}" >
                           <a href="{{ $articles->url($articles->currentPage()+1) }}" >
                              <i class="material-icons">chevron_right</i></a></li>
                     @endif
                  </ul>
               </div>
            @endif
         @endif

      </center>
   </div>
   <div id="grid">
      <div class="row" id="load-data">
         @foreach($art as $article)
            <div class="col s12 m4">
               <div class="card">
                  <div class="card-image">
                     <img src="https://picsum.photos/150/150?random=3">
                     <span class="card-title">{{$article->title ?? ''}}</span>
                  </div>
                  <div class="card-content">
                     <p></p>
                  </div>
                  <div class="card-action">
                     <ul class="view_selector">
                        <li> <a class="dark" href="{{ route('edit-article', ['id'=>$article->id ?? '']) }}"><i class="edit material-icons">edit</i></a> </li>
                        <li> <i class="material-icons delete" data-id="{{ $article->id ?? ''}}" data-title="{{$article->title ?? ''}}">delete</i></li>
                     </ul>
                  </div>
               </div>
            </div>
         @endforeach
      </div>
      @if(isset($art) && $total_rec > 10 )
         <div id="remove-row" >
            <center>
               <button class="waves-effect waves-light btn red" id="btn-more"  data-offset="{{ count($art) }}" >Load more</button>
            </center>
         </div>
      @endif
   </div>
   <div id="list">
      <ul class="collection" id="load-data2">
         @foreach($a as $ar)
            <li class="collection-item avatar article-item" data-id="{{$ar->id ?? ''}}">
               <img src="https://picsum.photos/150/200?random=3" alt="" class="circle">
               <span class="title"><b>{{$ar->title ?? ''}}</b></span>
               <a href="#!" class="secondary-content"> @if($ar->is_favorite == 1) <span class="material-icons is_favorite" data-id="{{ $ar->id ?? ''}}" data-val="0">star</span> @else <span class="material-icons is_favorite" data-id="{{ $ar->id ?? ''}}" data-val="1">star_border</span> @endif</a>
            </li>
         @endforeach
      </ul>
      <div class="sk-circle load">
         <div class="sk-circle1 sk-child"></div>
         <div class="sk-circle2 sk-child"></div>
         <div class="sk-circle3 sk-child"></div>
         <div class="sk-circle4 sk-child"></div>
         <div class="sk-circle5 sk-child"></div>
         <div class="sk-circle6 sk-child"></div>
         <div class="sk-circle7 sk-child"></div>
         <div class="sk-circle8 sk-child"></div>
         <div class="sk-circle9 sk-child"></div>
         <div class="sk-circle10 sk-child"></div>
         <div class="sk-circle11 sk-child"></div>
         <div class="sk-circle12 sk-child"></div>
      </div>
      <input type="hidden" id="total_count" value="{{ $total_rec ?? 0 }}" >
   </div>
   <!-- floating buttons -->
   <div class="fixed-action-btn">
      <a href="{{ route('create-article') }}" class="btn-floating btn-large red ">
         <i class="large material-icons">add</i>
      </a>
   </div>
   <!-- Modal Structure -->
   <div id="modal123" class="modal form-modal bottom-sheet">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat right"><i class="material-icons large" >close</i></a>

      <div class="modal-content">
         <h4>Form</h4>
      </div>
   </div>
</div>

   <div id="modal123" class="modal form-modal bottom-sheet">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat right"><i class="material-icons large" >close</i></a>

      <div class="modal-content">
         <h4>New Category</h4>
         <form class="col s12" id="category_form">
            <input type="hidden" name="eid" id="eid" value="">
            @csrf
            <div class="row">
               <div class="input-field col s12">
                  <input id="title" type="text" name="title" id="title" class="validate">
                  <label for="title">Title</label>
               </div>
            </div>
            <div class="row">
               <div class="input-field col s12">
                  <textarea id="description" name="description" class="materialize-textarea"></textarea>
                  <label for="textarea1">Category Description</label>
               </div>
            </div>
            <div class="row">
               <button id="cat_btn" form="category_form" class="waves-effect waves-light btn red darken-4 right small">Submit</button>
            </div>
         </form>
      </div>
   </div>
@endsection
@section('scripts')
<script>
   @if (isset($user_pref->value))
      $('.selector').removeClass('act');
      let sel = $('.selector');
      @switch($user_pref->value)
         @case('table')
         $("[data-val^='table']").addClass('act');
         $('#list').hide();
         $('#grid').hide();
      @break
      @case('grid')
         $("[data-val^='grid']").addClass('act');
         $('#list').hide();
         $('#table').hide();
         $(document).on('click', '#btn-more', function () {
      let offset = $('#btn-more').data('offset');
      let element = $('#load-data');

      $.ajax({
         url: "{{ route('load-more-articles') }}",
         type: "GET",
         data: {offset: offset},
         //dataType: "json",
         beforeSend: function () {
            $('#btn-more').prop('disabled', true);
            $("#btn-more").html('Loading..');
         },
         success: function (data) {
            offset += data.offset;
            $('#remove-row').remove();
            $.each(data.items, function (key, value) {
               let url = '';
               element.append('<div class="col s12 m4">' +
                       '<div class="card">' +
                       '<div class="card-image">' +
                       '<img src="https://picsum.photos/150/150?random=3">' +
                       '<span class="card-title">'+value.title+'</span>' +
                       '</div>' +
                       '<div class="card-content">' +
                       '<p></p>' +
                       '</div>' +
                       '<div class="card-action">' +
                       '<ul class="view_selector">'+
                       '<li> <a class="dark" href="'+url+'"><i class="edit material-icons">edit</i></a> </li>'+
                       '<li> <i class="material-icons delete" data-id="'+value.id+'" data-title="'+value.title+'">delete</i></li>'+
                       '</ul>'+
                       '</div>' +
                       '</div>' +
                       '</div>');
               $('#btn-more').prop('disabled', true).prop('disabled', false);

            });//end of each loop
         },
         error: function(data){
            $('#btn-more').hide();
            console.log('No data');
         }
      });

   });
      @break
      @case('list')
         $("[data-val^='list']").addClass('act');
         $('#table').hide();
         $('#grid').hide();

   windowOnScroll();
   function windowOnScroll() {
      $(window).scroll(function() {
         if($(window).scrollTop() + $(window).height() >= ($(document).height()*0.7)) {
            if($(".article-item").length < $("#total_count").val()) {
               let lastId = $(".article-item:last").data("id");
               getMoreData(lastId);
            }
         }
      });
   }
   function getMoreData(lastId) {
      let limit = 10;
      let element = $('#load-data2');
      $(window).off("scroll");
      $.ajax({
         url: "{{ route('load-more') }}",
         type: "GET",
         data: {last_id: lastId, limit: limit},
         beforeSend: function () {
            $('.load').show();
         },
         success: function (data) {
            setTimeout(function() {
               $('.load').hide();
               $.each(data.items, function (key, value) {
                  let url = '';
                  let favorite = (value.is_favorite == 1) ? '<span class="material-icons is_favorite" data-id="' + value.id + '" data-val="0">star</span>' : '<span class="material-icons is_favorite" data-id="' + value.id + '" data-val="1">star_border</span>';
                  element.append('<li class="collection-item avatar article-item" id="'+value.id+'">' +
                          '<img src="https://picsum.photos/150/200?random=3" alt="" class="circle">' +
                          '<span class="title"><b>' + value.title + '</b></span>' +
                          '<p>' + url + '</p>' + '<a href="#!" class="secondary-content">' + favorite + '</a>' +
                          '</li>');
                  windowOnScroll();
               });
            }, 1000);
         }, error: function (data) {
            $('.load').hide();
            console.log('No data');
         }
      });
   }
      @break
      @endswitch
   @else
      $("[data-val^='table']").addClass('act');
      $('#list').hide();
      $('#grid').hide();
   @endif

   $('.progress').hide();
   $('.fixed-action-btn').floatingActionButton();
   $('.modal').modal();
   $('.tooltipped').tooltip();
   $('.load').hide()
   $(document).on('click', '.selector', function(){
      $('.selector').removeClass('act');
      let selection = $(this).data('val');

      switch(selection) {
         case "table":
            $('#table').fadeIn();
            $('#list').fadeOut();
            $('#grid').fadeOut();
            break;
         case "grid":
            $('#grid').fadeIn();
            $('#list').fadeOut();
            $('#table').fadeOut();
            break;
         case "list":
            $('#list').fadeIn();
            $('#grid').fadeOut();
            $('#table').fadeOut();
            break;
         default: //do nothing
      }

      $(this).addClass('act');

      if (selection != "") {

         $.ajax({
            url: "{{ route('user-preference') }}",
            type: "POST",
            data: {key:'my_article_view',value:selection},
            success: function(result){
               if(result.success == '1'){
                  // setTimeout(function () {
                  //     location.reload();
                  // }, 500);
               } else {
                  M.toast({html: result.message})
               }
            }
         });
      }
   });
   $(document).on('change', '.is_active', function () {
      let cur_status = 0;
      let id = $(this).data("id");

      if ($(this).is(":checked")) {
         cur_status = 1;
      }

      $.ajax({
         url: "{{ route('update-article') }}",
         type: "POST",
         data: {id:id,status:cur_status},
         success: function(result){
            if(result.success == '1'){
               let data = JSON.parse(result.data);
               let isActive = data.active == 1 ? 'checked="checked"' : '';
               let today  = new Date(data.created_at);
               let url = data.url;
               let isFavorite = data.is_favorite == 1 ?  '<span class="material-icons is_favorite" data-id="'+data.id+'" data-val="0">star</span>' : '<span class="material-icons is_favorite" data-id="'+data.id+'" data-val="1">star_border</span>';

                       $('#tr_' + data.id).html('<td>' + data.title + '</td>' +
                       '<td>' + today.toLocaleDateString("en-US", options) + '</td>' +
                       '<td>' + isFavorite + '</td>' +
                       '<td>' + data.views + '</td>' +
                       '<td>' +
                       '   <label>' +
                              '<input type="checkbox" class="is_active" data-id="' + data.id + '" '+isActive+' />' +
                              '<span></span>' +
                           '</label>' +
                       '</td>' +
                       '<td>' +
                           '<ul class="view_selector">' +
                              '<li> <a class="dark" href="'+url+'"><i class="edit material-icons">edit</i></a></li>' +
                              '<li> <i class="material-icons delete" data-id="' + data.id + '" data-title="' + data.title + '">delete</i></li>' +
                           '</ul>' +
                       '</td>');
            } else {
               M.toast({html: result.message})
            }
         }
      });
   });
   $(document).on('click', '.delete', function(){
      let id = $(this).data("id");
      let article = $(this).data("title");

      $('.progress').fadeIn();

      $('.modal-content').html('');
      $('.modal-content').html('<h4>Are you sure to delete article: '+article+' ?</h4>' +
              '<button class="waves-effect waves-light btn red darken-4 small del_btn" data-id="'+id+'">DELETE</button>'
      );
      $('.progress').fadeOut();
      $('.form-modal').modal('open');

   });
   $(document).on('click', '.del_btn', function(){
      $('.progress').fadeIn();
      let id = $(this).data("id");

      $.post("{{ route('delete-article') }}", {"id":id})
              .done(function(result) {
                 setTimeout(function () {
                    $('.progress').fadeOut();
                    location.reload();
                 }, 1000);
              })
              .fail(function(result) {
                 $('.progress').fadeOut();
                 M.toast({html: result.message})
              });
   });
   $(document).on('click', '.is_favorite', function () {
      let is_favorite = $(this).data("val");
      let id = $(this).data("id");

      $.ajax({
         url: "{{ route('update-article') }}",
         type: "POST",
         data: {id:id,favorite:is_favorite},
         success: function(result){
            if(result.success == '1'){
               let data = JSON.parse(result.data);
               let isActive = data.active == 1 ? 'checked="checked"' : '';
               let url = data.url;
               let isFavorite = data.is_favorite == 1 ?  '<span class="material-icons is_favorite" data-id="'+data.id+'" data-val="0">star</span>' : '<span class="material-icons is_favorite" data-id="'+data.id+'" data-val="1">star_border</span>';
               let today  = new Date(data.created_at);

               $('#tr_' + data.id).html('<td>' + data.title + '</td>' +
                       '<td>' + today.toLocaleDateString("en-US", options) + '</td>' +
                       '<td>' + isFavorite + '</td>' +
                       '<td>' + data.views + '</td>' +
                       '<td>' +
                       '   <label>' +
                       '<input type="checkbox" class="is_active" data-id="' + data.id + '" '+isActive+' />' +
                       '<span></span>' +
                       '</label>' +
                       '</td>' +
                       '<td>' +
                       '<ul class="view_selector">' +
                       '<li> <a class="dark" href="'+url+'"><i class="edit material-icons">edit</i></a></li>' +
                       '<li> <i class="material-icons delete" data-id="' + data.id + '" data-title="' + data.title + '">delete</i></li>' +
                       '</ul>' +
                       '</td>');
            } else {
               M.toast({html: result.message})
            }
         }
      });
   });
   $(document).on('click', '#btn-more', function () {
      let offset = $('#btn-more').data('offset');
      let element = $('#load-data');

      $.ajax({
         url: "{{ route('load-more-articles') }}",
         type: "GET",
         data: {offset: offset},
         //dataType: "json",
         beforeSend: function () {
            $('#btn-more').prop('disabled', true);
            $("#btn-more").html('Loading..');
         },
         success: function (data) {
            offset += data.offset;
            $('#remove-row').remove();
            $.each(data.items, function (key, value) {
               let url = '';
               element.append('<div class="col s12 m4">' +
                       '<div class="card">' +
                       '<div class="card-image">' +
                       '<img src="https://picsum.photos/150/150?random=3">' +
                       '<span class="card-title">'+value.title+'</span>' +
                       '</div>' +
                       '<div class="card-content">' +
                       '<p></p>' +
                       '</div>' +
                       '<div class="card-action">' +
                       '<ul class="view_selector">'+
                       '<li> <a class="dark" href="'+url+'"><i class="edit material-icons">edit</i></a> </li>'+
                       '<li> <i class="material-icons delete" data-id="'+value.id+'" data-title="'+value.id+'">delete</i></li>'+
                       '</ul>'+
                       '</div>' +
                       '</div>' +
                       '</div>');
               $('#btn-more').prop('disabled', true).prop('disabled', false);

            });//end of each loop
         },
         error: function(data){
            $('#btn-more').hide();
            console.log('No data');
         }
      });

   });
   windowOnScroll();
   function windowOnScroll() {
      $(window).scroll(function() {
         if($(window).scrollTop() + $(window).height() >= ($(document).height()*0.7)) {
            if($(".article-item").length < $("#total_count").val()) {
               let lastId = $(".article-item:last").data("id");
               getMoreData(lastId);
            }
         }
      });
   }
   function getMoreData(lastId) {
      let limit = 10;
      let element = $('#load-data2');
      $(window).off("scroll");
      $.ajax({
         url: "{{ route('load-more') }}",
         type: "GET",
         data: {last_id: lastId, limit: limit},
         beforeSend: function () {
            $('.load').show();
         },
         success: function (data) {
            setTimeout(function() {
               $('.load').hide();
               $.each(data.items, function (key, value) {
                  let url = '';
                  let favorite = (value.is_favorite == 1) ? '<span class="material-icons is_favorite" data-id="' + value.id + '" data-val="0">star</span>' : '<span class="material-icons is_favorite" data-id="' + value.id + '" data-val="1">star_border</span>';
                  element.append('<li class="collection-item avatar article-item" id="'+value.id+'">' +
                          '<img src="https://picsum.photos/150/200?random=3" alt="" class="circle">' +
                          '<span class="title"><b>' + value.title + '</b></span>' +
                          '<p>' + url + '</p>' + '<a href="#!" class="secondary-content">' + favorite + '</a>' +
                          '</li>');
                  windowOnScroll();
               });
            }, 1000);
         }, error: function (data) {
            $('.load').hide();
            console.log('No data');
         }
      });
   }
</script>
@endsection
