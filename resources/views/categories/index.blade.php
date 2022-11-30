@extends('layouts.app')
@section('seo')

@endsection
@section('title')
    Categories
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
    <div class="progress">
        <div class="indeterminate"></div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col m10">
                <h4>Categories</h4>
                <a class="waves-effect waves-light btn red left waves-effect waves-light btn openModal">Create Category</a>
            </div>
            <div class="col m2">
                <ul class="view_selector right-align">
                    <li><i class="material-icons selector act" data-val="table">border_all</i></li>
                    <li><i class="material-icons selector" data-val="grid">grid_on</i></li>
                    <li><i class="material-icons selector" data-val="list">format_list_bulleted</i></li>
                </ul>
            </div>
        </div>
            <div id="table">
                <table class="responsive highlight striped" id="category-table">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Date Created</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr id="tr_{{$category->id ?? ''}}">
                            <td>{{$category->title ?? ''}}</td>
                            <td>{{$category->description ?? ''}}</td>
                            <td>{{Carbon\Carbon::parse($category->created_at)->isoFormat('dddd, MMMM D, Y')}}</td>
                            <td>
                                <label>
                                    <input type="checkbox" class="is_active" data-id="{{ $category->id ?? ''}}" {{ $category->active == 1 ? 'checked="checked"' : '' }}  />
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <ul class="view_selector">
                                    <li> <i class="edit material-icons" data-id="{{ $category->id ?? ''}}">edit</i></li>
                                    <li> <i class="material-icons delete" data-id="{{ $category->id ?? ''}}" data-title="{{$category->title ?? ''}}">delete</i></li>
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr id="del_row">
                            <td colspan="100%"><center>No Records available</center></td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <center>

                    @if (isset($categories))
                        @if ($categories->lastPage() > 1)
                            <div class="container" >
                                <ul class="pagination">
                                    @if($categories->currentPage() != 1)
                                        <li class="{{ ($categories->currentPage() == 1) ? 'disabled' : 'waves-effect' }}">
                                            <a href="{{ $categories->url(1) }}">
                                                <i class="material-icons">chevron_left</i>
                                            </a>
                                        </li>
                                    @endif
                                    @for ($i = 1; $i <= $categories->lastPage(); $i++)
                                        <li class="waves-effect {{ ($categories->currentPage() == $i) ? 'active red' : '' }}">
                                            <a href="{{ $categories->url($i) }}" >{{ $i }}</a>
                                        </li>
                                    @endfor
                                    @if($categories->currentPage() != $categories->lastPage())
                                        <li class="waves-effect {{ ($categories->currentPage() == $categories->lastPage()) ? 'disabled' : '' }}" >
                                            <a href="{{ $categories->url($categories->currentPage()+1) }}" >
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
                    @forelse($cat as $category)
                        @php
                            //$enc_cat_id = encryptor('encrypt', $category->id);
                            $enc_cat_id = $category->id;
                        @endphp
                        <div class="col s12 m4" id="col-{{$category->id}}">
                            <div class="card">
                                <div class="card-image">
                                    <img src="https://picsum.photos/150/150?random=3">
                                    <span class="card-title">{{$category->title ?? ''}}</span>
                                </div>
                                <div class="card-content">
                                    <p>{{$category->description ?? ''}}</p>
                                </div>
                                <div class="card-action">
                                    <ul class="view_selector">
                                        <li> <i class="edit material-icons" data-id="{{$category->id ?? ''}}">edit</i></li>
                                        <li> <i class="material-icons delete" data-id="{{$category->id ?? ''}}" data-title="{{$category->title ?? ''}}">delete</i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="row">
                            <div class="col s12">No Record</div>
                        </div>
                    @endforelse
                </div>
                @if(isset($cat) && $total_rec > 5 )
                    <div id="remove-row" >
                        <center>
                            <button class="waves-effect waves-light btn red" id="btn-more"  data-offset="{{ count($cat) }}" >Load more</button>
                        </center>
                    </div>
                @endif
            </div>
            <div id="list">
                @if(!$list->isEmpty())
                    <ul class="collection" id="load-data2">
                        @foreach($list as $category)
                            <li class="collection-item avatar article-item" data-id="{{$category->id ?? ''}}" id="li-{{$category->id ?? ''}}">
                                <img src="https://picsum.photos/150/200?random=3" alt="" class="circle">
                                <span class="title"><b>{{$category->title ?? ''}}</b></span>
                                <i class="title edit material-icons tooltipped" data-position="top" data-tooltip="Edit Category" data-id="{{$category->id ?? ''}}">edit</i>
                                <i class="title material-icons delete tooltipped" data-position="top" data-tooltip="Delete Category" data-id="{{$category->id ?? ''}}" data-title="{{$category->title ?? ''}}">delete</i>
                                <p>
                                    {{ $category->description ?? '' }}
                                </p>
                                <a href="#!" class="secondary-content">
                                    <label>
                                        <input type="checkbox" class="is_active" data-id="{{ $category->id ?? ''}}" {{ $category->active == 1 ? 'checked="checked"' : '' }}  />
                                        <span></span>
                                    </label>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="row">
                        <div class="col s12">No Record</div>
                    </div>
                @endif
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
            <a class="btn-floating btn-large red ">
                <i class="large material-icons openModal">add</i>
            </a>
        </div>
    </div>
    <!-- Modal Structure -->
    <div class="modal del-modal bottom-sheet">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat right"><i class="material-icons large" >close</i></a>
        <div class="modal-content"></div>
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
                @break

                @case('list')
                    $("[data-val^='list']").addClass('act');
                    $('#table').hide();
                    $('#grid').hide();
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

        $('.load').hide()

        $('.selector').click(function() {
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
                    data: {key:'cat_view',value:selection},
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
                url: "{{ route('update-category') }}",
                type: "POST",
                data: {id:id,status:cur_status},
                dataType: 'json',
                success: function(result){
                    if(result.success == '1'){
                        let data = JSON.parse(result.data);
                        let dt  = new Date(data.created_at);

                        let isActive = data.active == 1 ? 'checked="checked"' : '';

                        $('#tr_' + data.id).html('<td>' + data.title + '</td>' +
                            '<td>' + data.description + '</td>' +
                            '<td>' + dt.toLocaleDateString("en-US", options) + '</td>' +
                            '<td>' +
                            '   <label>' +
                            '<input type="checkbox" class="is_active" data-id="' + data.id + '" '+isActive+' />' +
                            '<span></span>' +
                            '</label>' +
                            '</td>' +
                            '<td>' +
                            '<ul class="view_selector">' +
                            '<li> <i class="edit material-icons" data-id="' + data.id + '">edit</i></li>' +
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
            //$('.load').show();
            $.ajax({
                url: "{{ route('load-more-categories') }}",
                method: "GET",
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
                        element.append('<div class="col s12 m4">' +
                            '<div class="card">' +
                            '<div class="card-image">' +
                            '<img src="https://picsum.photos/150/150?random=3">' +
                            '<span class="card-title">'+value.title+'</span>' +
                            '</div>' +
                            '<div class="card-content">' +
                            '<p>'+value.description+'</p>' +
                            '</div>' +
                            '<div class="card-action">' +
                            '<ul class="view_selector">'+
                            '<li><i class="edit material-icons" data-id="'+value.id+'">edit</i></li>'+
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

        $(document).on('click', '.delete', function(){
           let id = $(this).data("id");
           let cat = $(this).data("title");

            $('.del-modal > .modal-content').html('');
            $('.del-modal > .modal-content').html('<h4>Are you sure to delete category: '+cat+' ?</h4>' +
                    '<button class="waves-effect waves-light btn red darken-4 small del_btn" data-id="'+id+'">DELETE</button>'
            );
            $('.del-modal').modal('open');

        });

        $(document).on('click', '.del_btn', function(){
            let id = $(this).data("id");

            $.post("{{ route('delete-category') }}", {"id":id})
                .done(function(result) {
                    M.toast({html: result.message})
                    setTimeout(function () {
                        location.reload();
                    }, 500);
            })
            .fail(function(result) {
                M.toast({html: result.message})
            });
        });

        $(document).on('click', '.edit', function(){
            let id = $(this).data("id");

            $.ajax({
                url: "{{ route('view-category') }}",
                method: "GET",
                data:{id:id},
                dataType:'json',
                success: function(data){
                    $('.modal h4').html('Edit Category');
                    $('#title').val(data.title);
                    $('#description').val(data.description);
                    $('#eid').val(id);
                    $('.form-modal').modal('open');
                }
            });
        });

        $(document).on('click', '.openModal', function(){
            $('.modal h4').html('New Category');
            $('.form-modal').modal('open');
            $('#eid').val('');
            $('#title').val('');
            $('#description').val('');
        });

        $('#category_form').on('submit', function (e) {
            e.preventDefault();
            $('.progress').fadeIn();
            $('#cat_btn').prop('disabled',  true);

            let data = {};
            let url = '';

            let eid = $('#eid').val();
            let title = $('#title').val();
            let description = $('#description').val();

            if (eid == "") {
               url = "{{ route('store-category') }}";
               data = {title:title,description:description}
            } else {
                url = "{{ route('update-category') }}"
                data = {title:title,description:description,id:eid}
            }

            $.ajax({
                url: url,
                type: "POST",
                data: data,
                dataType: 'json',
                success: function(result){
                    if(result.success == '1'){

                        $('#category_form')[0].reset();
                        $("#cat_btn").html('Submit');
                        $('#cat_btn').prop('disabled',  false);
                        $('.form-modal').modal('close');

                        $('.progress').fadeOut();
                        M.toast({html: result.message});

                        let data = JSON.parse(result.data);
                        let dt  = new Date(data.created_at);
                        let isActive = data.active == 1 ? 'checked="checked"' : '';

                        if ($("#del_row").length) {
                            $("#del_row").remove();
                        }

                        if (eid == "") {
                                $('#category-table > tbody').prepend('<tr id="tr_'+data.id+'">' +
                                    '<td>' + data.title + '</td>' +
                                    '<td>' + data.description + '</td>' +
                                    '<td>' + dt.toLocaleDateString("en-US", options) + '</td>' +
                                    '<td>' +
                                    '   <label>' +
                                    '        <input type="checkbox" class="is_active" data-id="' + data.id + '" />' +
                                    '         <span></span>' +
                                    '    </label>' +
                                    '</td>' +
                                    '<td>' +
                                    '    <ul class="view_selector">' +
                                    '        <li> <i class="edit material-icons" data-id="' + data.id + '">edit</i></li>' +
                                    '        <li> <i class="material-icons delete" data-id="' + data.id + '" data-title="' + data.title + '">delete</i></li>' +
                                    '    </ul>' +
                                    '</td>' +
                                ' </tr>');

                                $('#load-data').prepend('<div class="col s12 m4">' +
                                    '<div class="card">' +
                                    '<div class="card-image">' +
                                    '<img src="https://picsum.photos/150/150?random=3">' +
                                    '<span class="card-title">'+data.title+'</span>' +
                                    '</div>' +
                                    '<div class="card-content">' +
                                    '<p>'+data.description+'</p>' +
                                    '</div>' +
                                    '<div class="card-action">' +
                                    '<ul class="view_selector">'+
                                    '<li><i class="edit material-icons" data-id="'+data.id+'">edit</i></li>'+
                                    '<li> <i class="material-icons delete" data-id="'+data.id+'" data-title="'+data.title+'">delete</i></li>'+
                                    '</ul>'+
                                    '</div>' +
                                    '</div>' +
                                    '</div>');

                                $('#load-data2').prepend('<li class="collection-item avatar article-item" id="'+data.id+'">' +
                                    '<img src="https://picsum.photos/150/200?random=3" alt="" class="circle">' +
                                    '<span class="title"><b>' + data.title + '</b></span>' +
                                    '<i class="title edit material-icons tooltipped" data-position="top" data-tooltip="Edit Category" data-id="'+data.id+'">edit</i>'+
                                    '<i class="title material-icons delete tooltipped" data-position="top" data-tooltip="Delete Category" data-id="'+data.id+'" data-title="'+data.title+'">delete</i>'+
                                    '<p>' + data.description + '</p>' +
                                    '<a href="#!" class="secondary-content">' +
                                    '<label>' +
                                    '<input type="checkbox" class="is_active" data-id="'+data.id+'" '+isActive+' /><span></span>' +
                                    '</label></a>' +
                                    '</li>');
                        } else {

                            $('#tr_' + data.id).html('<td>' + data.title + '</td>' +
                                '<td>' + data.description + '</td>' +
                                '<td>' + dt.toLocaleDateString("en-US", options) + '</td>' +
                                '<td>' +
                                '   <label>' +
                                '<input type="checkbox" class="is_active" data-id="' + data.id + '" '+isActive+' />' +
                                '<span></span>' +
                                '</label>' +
                                '</td>' +
                                '<td>' +
                                '<ul class="view_selector">' +
                                '<li> <i class="edit material-icons" data-id="' + data.id + '">edit</i></li>' +
                                '<li> <i class="material-icons delete" data-id="' + data.id + '" data-title="' + data.title + '">delete</i></li>' +
                                '</ul>' +
                            '</td>');

                            $('#col-'+data.id).html('<div class="card">' +
                                '<div class="card-image">' +
                                '<img src="https://picsum.photos/150/150?random=3">' +
                                '<span class="card-title">'+data.title+'</span>' +
                                '</div>' +
                                '<div class="card-content">' +
                                '<p>'+data.description+'</p>' +
                                '</div>' +
                                '<div class="card-action">' +
                                '<ul class="view_selector">'+
                                '<li><i class="edit material-icons" data-id="'+data.id+'">edit</i></li>'+
                                '<li> <i class="material-icons delete" data-id="'+data.id+'" data-title="'+data.title+'">delete</i></li>'+
                                '</ul>'+
                                '</div>' +
                                '</div>');

                            $('#li-' + data.id).html('<img src="https://picsum.photos/150/200?random=3" alt="" class="circle">' +
                                    '<span class="title"><b>'+data.title+'</b></span>' +
                                    '<i class="title edit material-icons tooltipped" data-position="top" data-tooltip="Edit Category" data-id="'+data.id+'">edit</i>' +
                                    '<i class="title material-icons delete tooltipped" data-position="top" data-tooltip="Delete Category" data-id="'+data.id+'" data-title="'+data.title+'">delete</i>' +
                                    '<p>'+data.description+'</p>' +
                                    '<a href="#!" class="secondary-content">'+
                                    '<label>'+
                                    '<input type="checkbox" class="is_active" data-id="'+data.id+'" '+isActive+'/>'+
                                    '<span></span>'+
                                    '</label>'+
                            '</a>');
                    }

                    } else {
                        M.toast({html: result.message})
                        $('.progress').fadeOut();
                        $("#cat_btn").html('Submit');
                        $('#cat_btn').prop('disabled',  false);
                    }
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
                url: "{{ route('load-more-cat') }}",
                type: "GET",
                data: {last_id: lastId, limit: limit},
                beforeSend: function () {
                    $('.load').show();
                },
                success: function (data) {
                    setTimeout(function() {
                        $('.load').hide();
                        $.each(data.items, function (key, value) {
                            let isActive = value.active === 1 ? 'checked="checked"' : '';
                            element.append('<li class="collection-item avatar article-item" id="'+value.id+'">' +
                                '<img src="https://picsum.photos/150/200?random=3" alt="" class="circle">' +
                                '<span class="title"><b>' + value.title + '</b></span>' +
                                '<p>' + value.description + '</p>' +
                                '<a href="#!" class="secondary-content">' +
                                '<label>' +
                                '<input type="checkbox" class="is_active" data-id="'+value.id+'" '+isActive+' /><span></span>' +
                                '</label></a>' +
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
