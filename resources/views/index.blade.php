<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

</head>
<style type="text/css">
    body{
        font-family: 'Roboto Mono', monospace;
    }
    h1{
        text-align: center;
        font-size:35px;
        font-weight:900;
    }
</style>
    
<body>
<div class="container">

    <div class="row">
    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }} <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></p>
        </div>
    @endif

    <form method="POST" action="{{route('index')}}">
        @csrf
        <div class="col col-md-3">
            <label>Start Date: </label>
            <div id="start" class="input-group date" data-date-format="yyyy-mm-dd">
                <input class="form-control" 
                        type="text" name="start_date" required />
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar"></i>
                </span>
            </div>
        </div>
        <div class="col col-md-3">
            <label>End Date: </label>
            <div id="end" class="input-group date" data-date-format="yyyy-mm-dd">
                <input class="form-control" 
                        type="text"  name="end_date" required />
                <span class="input-group-addon">
                <label></label>
                    <i class="glyphicon glyphicon-calendar"></i>
                </span>
            </div>
        </div>

        <div class="col col-md-2" style="margin-top:2%;">
            <div class="input-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

  </div>
<br>
  <div class="row">

    <div class="col col-md-8">
        <p>Fastest astroid id is : <span style="color:red;">{{$fastestAseroidId}}</span></p>
        <p>Fastest astroid speed is : <span style="color:red;">{{$fastestAseroid}}</span>&nbsp;km/h</p>
        <p>Closest astroid id is : <span style="color:red;">{{$closestAseroidId}}</span></p>
        <p>Closest astroid distance is : <span style="color:red;">{{$closestAseroid}}</span>&nbsp;km</p>
        <p>Average size of astroids is : <span style="color:red;">{{$averageSizeOfAstroids}}</span>&nbsp;km</p>
    </div>


</div>

    <canvas id="line-chart" width="800" height="250"></canvas>
</div>
</body>
  
<script type="text/javascript">
  
  
new Chart(document.getElementById("line-chart"), {
  type: 'line',
  data: {
    labels: {{ Js::from($asteroidsCountByDateKeys) }},
    datasets: [{ 
        data: {{ Js::from($asteroidsCountByDatevalues) }},
        label: "Chart of Asteroids",
        borderColor: "#3e95cd",
        fill: false
      }
    ]
  }
});
  
</script>

<script>
    $(function () {
        $("#start").datepicker({ 
            autoclose: true, 
            todayHighlight: true,
        }).datepicker('update', new Date());
    });
</script>

<script>
    $(function () {
        $("#end").datepicker({ 
            autoclose: true, 
            todayHighlight: true,
        }).datepicker('update', new Date());
    });
</script>
</html>