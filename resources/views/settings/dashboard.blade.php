@extends('settings.layouts.app')

@section('styles')
<style type="text/css" media="screen">
    .php-info pre {
        margin: 0;
        font-family: monospace;
    }
    .php-info a:link {
        color: #009;
        text-decoration: none;
        background-color: #ffffff;
    }
    .php-info a:hover {
        text-decoration: underline;
    }
    .php-info table {
        border-collapse: collapse;
        border: 0;
        width: 100%;
        box-shadow: 1px 2px 3px #ccc;
    }
    .php-info .center {
        text-align: center;
    }
    .php-info .center table {
        margin: 1em auto;
        text-align: left;
    }
    .php-info .center th {
        text-align: center !important;
    }
    .php-info td {
        border: 1px solid #666;
        font-size: 75%;
        vertical-align: baseline;
        padding: 4px 5px;
    }
    .php-info th {
        border: 1px solid #666;
        font-size: 75%;
        vertical-align: baseline;
        padding: 4px 5px;
    }
    .php-info h1 {
        font-size: 150%;
    }
    .php-info h2 {
        font-size: 125%;
    }
    .php-info .p {
        text-align: left;
    }
    .php-info .e {
        background-color: #ccf;
        width: 50px;
        font-weight: bold;
    }
    .php-info .h {
        background-color: #99c;
        font-weight: bold;
    }
    .php-info .v {
        background-color: #ddd;
        max-width: 50px;
        overflow-x: auto;
        word-wrap: break-word;
    }
    .php-info .v i {
        color: #999;
    }
    .php-info img {
        float: right;
        border: 0;
    }
    .php-info hr {
        width: 100%;
        background-color: #ccc;
        border: 0;
        height: 1px;
    }
</style>
@endsection

{{-- @section('breadcrumbs', Breadcrumbs::render('settings.dashboard')) --}}

@section('content')
<div class="header">
    <h3 class="header-title">Reports</h3>
</div>	
<div class="pure-g box-content">   
    <div class="pure-u-1 pure-u-md-1">         
        {{-- <canvas id="myChart" width="400" height="260"></canvas>  --}}
        <div class="php-info">
            {{-- @php
                ob_start();
                phpinfo();
                $pinfo = ob_get_contents();
                ob_end_clean();
                $pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
                echo $pinfo;
            @endphp --}}
</div>
    </div>
</div>

{{-- <div class="pure-u-1-2 box">
    <div class="l-box">
    <div class="header">
        <h3 class="header-title">Message</h3>
    </div>	
    <div class="box-content"> 
        <div class="box-content">
            <form class="pure-form pure-form-stacked">
                <div class="pure-g">
                    <div class="pure-u-1-1">
                        <label for="title">Title</label>
                        <input id="title" type="text" class="pure-u-1-1">

                        <label for="post">Post Content</label>
                        <textarea id="post" rows="10" class="pure-u-1-1"></textarea>

                        <hr>

                        <button class="pure-button pure-button-primary">Save</button>
                        <button class="pure-button">Save in Draft</button>
                    </div>
                </div>
            </form>
        </div>            
    </div>
    </div>
</div> --}}
@endsection

@section('after-scripts')
 <!-- <script src="bower_components/chart.js/dist/Chart.min.js"></script> -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script> --}}
{{-- <script>
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["2017", "2018", "2019"],
                datasets: [{
                    label: 'Number of published datasets',
                    data: [12, 14, 8, 2, 5, 1],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                        // 'rgba(75, 192, 192, 0.2)',
                        // 'rgba(153, 102, 255, 0.2)',
                        // 'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                        // 'rgba(75, 192, 192, 1)',
                        // 'rgba(153, 102, 255, 1)',
                        // 'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    </script> --}}
@endsection
