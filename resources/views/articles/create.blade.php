@extends('layouts.app')
@section('seo')

@endsection
@section('title')
    New Article
@endsection
@section('css')
{{--    <link href="https://cdn.quilljs.com/1.0.5/quill.snow.css" rel="stylesheet">--}}
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code table lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
        });
    </script>
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
        .ql-editor strong{
            font-weight:bold;
        }
    </style>
    <div class="progress">
        <div class="indeterminate"></div>
    </div>
    <div class="container-fluid" style="margin-top: 50px;">
        <h4 style="text-align: center;"><strong>Create Article</strong></h4>
{{--        <div class="card s12">--}}
            <div class="row" style="padding: 20px;">
                <div class="col sm12 m12">
                    <form class="col s12" id="post_form">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="title" type="text" name="title" id="title" class="validate" >
                                <label for="email">Title</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select name="category" id="category">
                                    <option value="" disabled selected>Choose a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                                <label>Select Category</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="chips chips-placeholder tooltipped col s12" data-position="top" data-tooltip="Keywords are important for seo and for other user to search your article more quickly"></div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12">
                                <textarea id="description">Please put your article contents here</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <button id="post_btn" form="post_form" class="waves-effect waves-light btn red darken-4 right">Submit Article</button>
                        </div>
                    </form>
                </div>
            </div>
{{--        </div>--}}
    </div>
    <div class="progress">
        <div class="indeterminate"></div>
    </div>
@endsection
@section('scripts')
<script>
        tinymce.init({
                selector: 'textarea#description', // Replace this CSS selector to match the placeholder element for TinyMCE
                plugins: 'code table lists',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
        });

        $(document).ready(function(){
            $('.progress').hide();
            $('select').formSelect();
            $('.chips').chips();
            $('.tooltipped').tooltip();
            $('.chips-placeholder').chips({
                placeholder: 'Enter a keyword for your article'
            });
        });

        $('#post_form').on('submit', function (e) {
            e.preventDefault();
            $('.progress').fadeIn();
            $('#post_btn').prop('disabled',  true);

            let description = tinymce.get('description').getContent();;
            let title = $('#title').val();
            let category = $('#category').val();
            let keywords = JSON.stringify(M.Chips.getInstance($('.chips')).chipsData)

            $.ajax({
                url: "{{ route('store-article') }}",
                type: "POST",
                data:{title:title,description:description,keywords:keywords,category:category},
                dataType: 'json',
                success: function(result){
                    if(result.success == '1'){
                        M.toast({html: result.message})
                        $('.progress').fadeOut();
                        $('#post_form')[0].reset();
                        tinymce.activeEditor.setContent('<p>Please put your article contents here</p>');
                        $("#post_btn").html('Submit');
                        $('#post_btn').prop('disabled',  false);
                        $('.chips').chips({
                            data: null,
                        });
                    } else {
                        M.toast({html: result.message})
                        $('.progress').fadeOut();
                        $("#post_btn").html('Submit');
                        $('#post_btn').prop('disabled',  false);
                    }
                }
            });
        });
    </script>
@endsection
