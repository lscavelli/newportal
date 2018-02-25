@extends('layouts.admin')

@section('breadcrumb')
	<section class="content-header">
		<h1>
			Dashboard
			<small>Control panel</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>
@endsection

@section('content')
<section class="content">
	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>{{$users}}</h3>
					<p>Users</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-stalker"></i>
				</div>
				<a href="{{url('/admin/users')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{$roles}}</h3>
					<p>Roles</p>
				</div>
				<div class="icon">
					<i class="fa fa-user-plus"></i>
				</div>
				<a href="{{url('/admin/roles')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>{{$permissions}}</h3>
					<p>Permissions</p>
				</div>
				<div class="icon">
					<i class="fa fa-key"></i>
				</div>
				<a href="{{url('/admin/permissions')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3>53<sup style="font-size: 20px">%</sup></h3>
					<p>Gruppi</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>
				<a href="{{url('/admin/groups')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Attività utente</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="chart">
						<canvas id="areaChart" style="height:250px"></canvas>
					</div>
				</div>
				<!-- /.box-body -->
			</div>
		</div>
	</div>
</section>
@endsection
@section('scripts')
	<!-- ChartJS 1.0.1 -->
	{!! Html::script("/node_modules/chart.js/Chart.min.js") !!}
<script>
    $(function () {
        //--------------
        //- AREA CHART -
        //--------------

		$.getJSON("/admin/dashboard/data", function (result) {

			var labels = [], data=[];
			for (var i = 0; i < result.length; i++) {
				labels.push(result[i].label);
				data.push(result[i].activity);
			}

			// Get context with jQuery - using jQuery's .get() method.
			var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
			// This will get the first returned node in the jQuery collection.
			var areaChart = new Chart(areaChartCanvas);

			var areaChartData = {
				labels: labels,
				datasets: [
				{
					label: "Attività",
					fillColor: "rgba(210, 214, 222, 1)",
					strokeColor: "rgba(210, 214, 222, 1)",
					pointColor: "rgba(124, 151, 206, 1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "#97a4be",
					data: data
				}
				/*
				,{
					label: "Digital Goods",
					fillColor: "rgba(60,141,188,0.9)",
					strokeColor: "rgba(60,141,188,0.8)",
					pointColor: "#3b8bba",
					pointStrokeColor: "rgba(60,141,188,1)",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(60,141,188,1)",
				}*/
				]
			};

			var areaChartOptions = {
				//Boolean - If we should show the scale at all
				showScale: true,
				//Boolean - Whether grid lines are shown across the chart
				scaleShowGridLines: false,
				//String - Colour of the grid lines
				scaleGridLineColor: "rgba(0,0,0,.05)",
				//Number - Width of the grid lines
				scaleGridLineWidth: 1,
				//Boolean - Whether to show horizontal lines (except X axis)
				scaleShowHorizontalLines: true,
				//Boolean - Whether to show vertical lines (except Y axis)
				scaleShowVerticalLines: true,
				//Boolean - Whether the line is curved between points
				bezierCurve: true,
				//Number - Tension of the bezier curve between points
				bezierCurveTension: 0.3,
				//Boolean - Whether to show a dot for each point
				pointDot: true,
				//Number - Radius of each point dot in pixels
				pointDotRadius: 4,
				//Number - Pixel width of point dot stroke
				pointDotStrokeWidth: 1,
				//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
				pointHitDetectionRadius: 20,
				//Boolean - Whether to show a stroke for datasets
				datasetStroke: true,
				//Number - Pixel width of dataset stroke
				datasetStrokeWidth: 2,
				//Boolean - Whether to fill the dataset with a color
				datasetFill: true,
				//String - A legend template
				legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
				  //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
				 maintainAspectRatio: true,
				  //Boolean - whether to make the chart responsive to window resizing
				  responsive: true
			};

			//Create the line chart
			areaChart.Line(areaChartData, areaChartOptions);
		  });
      });

</script>
@stop
