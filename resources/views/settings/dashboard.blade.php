@extends('settings.layouts.app')

{{-- @section('breadcrumbs', Breadcrumbs::render('settings.dashboard')) --}}

@section('content')
<div class="header">
    <h3 class="header-title">Reports</h3>
</div>	
<div class="pure-g box-content">   
    <div class="pure-u-1 pure-u-md-2-3">         
        <canvas id="myChart" width="400" height="260"></canvas> 
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script>
<script>
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 14, 8, 2, 5, 1],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
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
    </script>
@endsection
