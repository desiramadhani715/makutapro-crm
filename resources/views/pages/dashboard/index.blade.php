@extends('layouts.simple.master')

@section('title', 'Dashboard')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/chartist.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/scrollbar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/prism.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/whether-icon.css')}}">
@endsection

@section('style')
<style>
	.sales-activity {
		height: 400px;
		width: 500;
		overflow-y: scroll;
	}
	.sales-activity::-webkit-scrollbar {
		width: 0.5em;
	}

	.sales-activity::-webkit-scrollbar-thumb {
		background-color: #3178b62f;
		border-radius: 0.5em;
	}

	.sales-activity::-webkit-scrollbar-track {
		background-color: transparent;
	}
</style>
@endsection

@section('breadcrumb-title')
{{-- <h3>Default</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-xl-12 xl-100 chart_data_left box-col-12">
			<div class="card">
				<div class="card-body p-0">
					<div class="row m-0 chart-main">
						<div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
							<div class="media align-items-center">
								<div class="hospital-small-chart">
									<div class="small-bar">
										<div class="small-chart flot-chart-container"></div>
									</div>
								</div>
								<div class="media-body">
									<div class="right-chart-content">
										<h4>{{$total}}</h4>
										<span>Total Leads</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
							<div class="media align-items-center">
								<div class="hospital-small-chart">
									<div class="small-bar">
										<div class="small-chart1 flot-chart-container"></div>
									</div>
								</div>
								<div class="media-body">
									<div class="right-chart-content">
										<h4>{{$process}}</h4>
										<span>In Process</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
							<div class="media align-items-center">
								<div class="hospital-small-chart">
									<div class="small-bar">
										<div class="small-chart2 flot-chart-container"></div>
									</div>
								</div>
								<div class="media-body">
									<div class="right-chart-content">
										<h4>{{$closing}}</h4>
										<span>Closing</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
							<div class="media border-none align-items-center">
								<div class="hospital-small-chart">
									<div class="small-bar">
										<div class="small-chart3 flot-chart-container"></div>
									</div>
								</div>
								<div class="media-body">
									<div class="right-chart-content">
										<h4>{{$notinterest}}</h4>
										<span>Not Interested</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row second-chart-list third-news-update">
		<div class="col-xl-12 xl-100 dashboard-sec box-col-12">
			<div class="card earning-card">
				<div class="card-body p-0">
					<div class="row m-0">
						<div class="col-xl-3 earning-content p-0">
							<div class="row m-0 chart-left">
								<div class="col-xl-12 p-0 left_side_earning">
									<h5>Dashboard</h5>
									<p class="font-roboto" id="summaryLabel"></p>
								</div>
								<div class="col-xl-12 p-0 left_side_earning">
									<h5 id="totalLeads"></h5>
									<p class="font-roboto">Total</p>
								</div>
								<div class="col-xl-12 p-0 left_side_earning">
									<h5 id="processLeads"></h5>
									<p class="font-roboto">On Process</p>
								</div>
								<div class="col-xl-12 p-0 left_side_earning">
									<h5 id="notInterestLeads"></h5>
									<p class="font-roboto">Not Interest</p>
								</div>
								<div class="col-xl-12 p-0 left_side_earning">
									<h5 id="closingLeads"></h5>
									<p class="font-roboto">Closing</p>
								</div>
							</div>
						</div>
						<div class="col-xl-9 p-0">
							<div class="chart-right">
								<div class="row m-0 p-tb">
									<div class="col-xl-12 col-md-12 col-sm-12 col-12 p-0">
										<div class="inner-top-left">
											<ul class="d-flex list-unstyled" style="cursor: pointer;">
												<li id="daily" onclick="refreshChart(1)">Daily</li>
												<li id="weekly" onclick="refreshChart(7)">Weekly</li>
												<li id="monthly" onclick="refreshChart(30)">Monthly</li>
												<li id="yearly" onclick="refreshChart(365)">Yearly</li>
                                                <li>
                                                    <input class="form-control form-control-sm datepicker-here since" name="since" id="since" placeholder="Since" type="text" data-language="en">
                                                </li>
                                                <li>
                                                    <input class="form-control form-control-sm datepicker-here " name="to" id="to" placeholder="To" type="text" data-language="en">
                                                </li>
											</ul>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-12">
										<div class="card-body p-0">
											<div class="current-sale-container">
												<div id="chart-currently"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="row border-top m-0">
									<div class="col justify-content-center">
										<div class="inner-top-right">
											<ul class="d-flex list-unstyled justify-content-around">
												<li id="totalDigSource"></li>
												<li id="totalSalesSource"></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6 xl-50 box-col-6">
			<div class="card">
				<div class="card-header">
				   <div class="row">
					  <div class="col-9">
						 <h5>Platform Chart</h5>
						 <p class="pb-0" style="margin-bottom: -20px">This is a bar chart of Prospect by Platform</p>
					  </div>
					  <div class="col-3 text-end"><i class="text-muted" data-feather="navigation"></i></div>
				   </div>
				</div>
				<div class="card-body">
					<div id="platform-bar"></div>
				</div>
			 </div>
		</div>
		<div class="col-xl-6 xl-50 box-col-6">
			<div class="card">
				<div class="card-header">
				   <div class="row">
					  <div class="col-9">
						 <h5>Source Chart</h5>
						 <p class="pb-0" style="margin-bottom: -20px">This is a bar chart of Prospect by Source</p>
					  </div>
					  <div class="col-3 text-end"><i class="text-muted" data-feather="navigation"></i></div>
				   </div>
				</div>
				<div class="card-body">
				   <div class="chart-container">
					  <div id="source-bar"></div>
				   </div>
				</div>
			 </div>
		</div>
		<div class="col-xl-12 xl-100 notification box-col-12">
			<div class="card">
				<div class="card-header card-no-border">
					<div class="header-top">
						<h5 class="m-0">Sales Activity</h5>
						<div class="card-header-right-icon">
							{{-- <select class="button btn btn-primary">
								<option>Today</option>
								<option>Yesterday</option>
							</select> --}}
						</div>
					</div>
				</div>
				<div class="card-body pt-0 mb-5 sales-activity">
					@forelse ($historySales as $item)
					<div class="media">
						<div class="media-body">
							<p>{{ date("M j, Y",strtotime($item->created_at)) }} <span>| {{ date('h:i:s A', strtotime($item->created_at)) }}</span></p>
							{{-- <h6>{{ $item->subject_dev }}<span class="dot-notification"></span></h6> --}}
							<h6>{{ $item->subject_dev }}</span></h6>
							<span>{{ $item->notes_dev }}</span>
						</div>
					</div>
					@empty
						<div class="media">
							<div class="media-body text-center">
								<span>No Activity.</span>
							</div>
						</div>
					@endforelse
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var session_layout = '{{ session()->get('layout') }}';
</script>
@endsection

@section('script')
<script src="{{asset('assets/js/chart/chartist/chartist.js')}}"></script>
<script src="{{asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')}}"></script>
<script src="{{asset('assets/js/chart/knob/knob.min.js')}}"></script>
<script src="{{asset('assets/js/chart/knob/knob-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/stock-prices.js')}}"></script>
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('assets/js/dashboard/default.js')}}"></script>
<script src="{{asset('assets/js/notify/index.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/typeahead-custom.js')}}"></script>


<script src="{{asset('assets/js/prism/prism.min.js')}}"></script>
<script src="{{asset('assets/js/clipboard/clipboard.min.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
<script src="{{asset('assets/js/custom-card/custom-card.js')}}"></script>
<script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
<script src="{{asset('assets/js/general-widget.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/tooltip-init.js')}}"></script>

<script>
	function refreshChart($days){
		$("#daily").removeClass('active');
		$("#weekly").removeClass('active');
		$("#monthly").removeClass('active');
		$("#yearly").removeClass('active');
		$("#chart-currently").empty();

		var days = $days;
        var url = `/loadLeadsChart?days=${days}`;
        if (days != 1 && days != 7 && days != 30 && days != 365)
            url = `/loadLeadsChart?${days}`

        $.ajax({
			type:"GET",
			url: url,
			dataType: 'JSON',
			success:function(res){
				if(res){
					$("#totalLeads").html(res.total);
					$("#processLeads").html(res.inProcess);
					$("#notInterestLeads").html(res.notInterest);
					$("#closingLeads").html(res.closing);
					$("#summaryLabel").html(`Overview of ${res.summaryLabel}`);
					$("#totalDigSource").html(`Digital Source (${res.digSource} leads)`);
					$("#totalSalesSource").html(`Sales Source (${res.salesSource} leads)`);
					if (days == 1)
						$("#daily").addClass('active');
					if (days == 7)
						$("#weekly").addClass('active');
					if (days == 30)
						$("#monthly").addClass('active');
					if (days == 365)
						$("#yearly").addClass('active');

					var leadsChart = {
						series: [{
							name: 'Digital Source',
							data: res.countDigitalSource
						}, {
							name: 'Sales Source',
							data: res.countSalesSource
						}],
						chart: {
							height: 280,
							type: 'area',
							toolbar: {
								show: false
							},
						},
						dataLabels: {
							enabled: false
						},
						stroke: {
							curve: 'smooth'
						},
						xaxis: {
							type: 'category',
							low: 0,
							offsetX: 0,
							offsetY: 0,
							show: false,
							categories: res.dates,
							labels: {
								low: 0,
								offsetX: 0,
								show: false,
							},
							axisBorder: {
								low: 0,
								offsetX: 0,
								show: false,
							},
						},
						markers: {
							strokeWidth: 3,
							colors: "#ffffff",
							strokeColors: [ CubaAdminConfig.primary , CubaAdminConfig.secondary ],
							hover: {
								size: 6,
							}
						},
						yaxis: {
							low: 0,
							offsetX: 0,
							offsetY: 0,
							show: false,
							labels: {
								low: 0,
								offsetX: 0,
								show: true,
							},
							axisBorder: {
								low: 0,
								offsetX: 0,
								show: false,
							},
						},
						grid: {
							show: false,
							padding: {
								left: 0,
								right: 0,
								bottom: -15,
								top: -20
							}
						},
						colors: [ CubaAdminConfig.primary , CubaAdminConfig.secondary ],
						fill: {
							type: 'gradient',
							gradient: {
								shadeIntensity: 1,
								opacityFrom: 0.7,
								opacityTo: 0.5,
								stops: [0, 80, 100]
							}
						},
						legend: {
							show: false,
						},
						tooltip: {
							x: {
								format: 'dd-mm-yyy'
							},
							y: {
								formatter: function(val) {
									return val;
								}
							}
						},
					};

					var chart = new ApexCharts(document.querySelector("#chart-currently"), leadsChart);
					chart.render();

					// call this function to show the loading state
					function showLoading() {
						chart.showDataLabels();
					}

					// call this function to hide the loading state and display the actual chart data
					function hideLoading() {
						chart.hideDataLabels();
					}
				}
			}
        });

	}

	refreshChart(7);

    $(document).ready(function() {
        // Attach the 'since' datepicker with the onSelect option
        $("#since").datepicker({
            language: 'en',
            onSelect: function(dateText, inst) {
                const since = new Date(dateText);
                sinceValue = `since=${since.getFullYear()}-${('0' + (since.getMonth() + 1)).slice(-2)}-${since.getDate()}`;
                refreshChart(sinceValue);
            },
        });

        // Attach the 'to' datepicker with the onSelect option
        $("#to").datepicker({
            language: 'en',
            onSelect: function(dateText, inst) {
                const to = new Date(dateText);
                const toValue = `to=${to.getFullYear()}-${('0' + (to.getMonth() + 1)).slice(-2)}-${to.getDate()}`;

                // Combine 'since' and 'to' values
                const combinedValues = `${sinceValue}&${toValue}`;
                refreshChart(combinedValues);
            },
        });
    });

</script>



<script>
	// platform chart
	var options2 = {
		chart: {
			height: 350,
			type: 'bar',
			toolbar:{
			show: true
			}
		},
		plotOptions: {
			bar: {
				horizontal: true,
			}
		},
		dataLabels: {
			enabled: true
		},
		series: [{
			data: {!!JSON_encode($countPlatform)!!}
		}],
		xaxis: {
			categories: {!!JSON_encode($categoryPlatform)!!},
		},
		colors:[ CubaAdminConfig.orange_mkt ]
	}

	var chart2 = new ApexCharts(
		document.querySelector("#platform-bar"),
		options2
	);

	chart2.render();

</script>

<script>
	// source chart
	var options2 = {
		chart: {
			height: 350,
			type: 'bar',
			toolbar:{
			show: true
			}
		},
		plotOptions: {
			bar: {
				horizontal: true,
			}
		},
		dataLabels: {
			enabled: true
		},
		series: [{
			data: {!!JSON_encode($countSource)!!}
		}],
		xaxis: {
			categories: {!!JSON_encode($categorySource)!!},
		},
		colors:[ CubaAdminConfig.blue_mkt ]
	}

	var chart2 = new ApexCharts(
		document.querySelector("#source-bar"),
		options2
	);

	chart2.render();

</script>

@endsection
