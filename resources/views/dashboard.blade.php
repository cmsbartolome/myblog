@extends('layouts.app')
@section('seo')

@endsection
@section('title')
    Dashboard | {{ auth()->user()->name }}
@endsection
@section('content')
    <style>
        div.sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
        }
        .view_selector > li {
            display: inline;
        }
    </style>
<div class="container">
    <div class="row">
        <canvas id="canvas" height="280" width="600"></canvas>
    </div>
    <div class="row">
        <div class="col s12 m6">
            <canvas id="canvas2" height="280" width="600"></canvas>
        </div>
        <div class="col s12 m6">
            <canvas id="canvas3" height="280" width="600"></canvas>
        </div>
    </div>
    <div class="row">
        <div class="col m3">
            <div class="card lighten-1 dashboard-card" >
                <div class="card-content black-text">
                    <span class="card-title">
                        <center>
                            <strong>My Articles: {{ (int)$articles ?? 0 }}</strong>
                        </center>
                    </span>
                </div>
                <div class="card-action">
                    <center>
                        <a class="red-text" href="{{ route('articles') }}"><i class="large material-icons">insert_drive_file</i></a>
                    </center>
                </div>
            </div>
        </div>
        <div class="col m3">
            <div class="card lighten-1 dashboard-card" >
                <div class="card-content black-text">
                    <span class="card-title">
                        <center>
                            <strong>Categories: {{ (int)$categories ?? 0 }}</strong>
                        </center>
                    </span>
                </div>
                <div class="card-action">
                    <center>
                        <a class="red-text" href="{{ route('categories') }}"><i class="large material-icons">menu</i></a>
                    </center>
                </div>
            </div>
        </div>
        <div class="col m3">
            <div class="card lighten-1 dashboard-card">
                <div class="card-content black-text">
                    <span class="card-title">
                        <center>
                            <strong>Subcategories: {{ (int)$subcategories ?? 0 }}</strong>
                        </center>
                    </span>
                </div>
                <div class="card-action">
                    <center>
                        <a class="red-text" href="{{ route('subcategories') }}"><i class="large material-icons">menu</i></a>
                    </center>
                </div>
            </div>
        </div>
        <div class="col m3">
            <div class="card lighten-1 dashboard-card" >
                <div class="card-content black-text">
                    <span class="card-title">
                        <center>
                            <strong>Active users: {{ $actUsers ?? 0 }}</strong>
                        </center>
                    </span>
                </div>
                <div class="card-action">
                    <center>
                        <a class="red-text" href="#"><i class="large material-icons">assignment_ind</i></a>
                    </center>
                </div>
            </div>
        </div>
        <div class="col m3">
            <div class="card lighten-1 dashboard-card" >
                <div class="card-content black-text">
                    <span class="card-title">
                        <center>
                            <strong>Inactive users: {{ $inactUsers ?? 0 }}</strong>
                        </center>
                    </span>
                </div>
                <div class="card-action">
                    <center>
                        <a class="black-text" href="#"><i class="large material-icons">assignment_ind</i></a>
                    </center>
                </div>
            </div>
        </div>
        <div class="col m3">
            <div class="card lighten-1 dashboard-card" >
                <div class="card-content black-text">
                    <span class="card-title">
                        <center>
                            <strong>Messages</strong>
                        </center>
                    </span>
                </div>
                <div class="card-action">
                    <center>
                        <a class="red-text" href="#"><i class="large material-icons">chat</i></a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
    let year = {{ $years }};
    let user = {{ $users }};
    let barChartData = {
        labels: year,
        datasets: [{
            label: 'User',
            backgroundColor: "#D32F2F",
            data: user
        }]
    };

    window.onload = function() {
        let ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'line', // or line, radar, pie, polarArea, bubble, scatter, bar
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Yearly User Joined'
                }
            }
        });
    };


    {{--var year = ['2013','2014','2015', '2016'];--}}
    {{--var data_click = <?php echo $click; ?>;--}}
    {{--var data_viewer = <?php echo $viewer; ?>;--}}

    // let barChartData2 = {
    //     labels: year,
    //     datasets: [{
    //         label: 'Click',
    //         backgroundColor: "rgba(220,220,220,0.5)",
    //         data: data_click
    //     }, {
    //         label: 'View',
    //         backgroundColor: "rgba(151,187,205,0.5)",
    //         data: data_viewer
    //     }]
    // };


    // window.onload = function() {
    //     let ctx2 = document.getElementById("canvas2").getContext("2d");
    //     window.myBar = new Chart(ctx2, {
    //         type: 'bar',
    //         data: barChartData2,
    //         options: {
    //             elements: {
    //                 rectangle: {
    //                     borderWidth: 2,
    //                     borderColor: 'rgb(0, 255, 0)',
    //                     borderSkipped: 'bottom'
    //                 }
    //             },
    //             responsive: true,
    //             title: {
    //                 display: true,
    //                 text: 'Yearly Website Visitor'
    //             }
    //         }
    //     });
    // };


        $(document).ready(function(){
            $('.carousel').carousel();
            $('.fixed-action-btn').floatingActionButton();
            $('.parallax').parallax();
        });
    </script>
@endsection
