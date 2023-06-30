@extends('layouts.simple.master')
@section('title', 'Demographics')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/echart.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Demographics</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Demographics</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-xl-6 box-col-12">
			<div class="card">
				<div class="card-header">
					<h5>Leads Based on Age</h5>
				</div>
				<div class="card-body">
					<div class="apache-cotainer" id="echart-pie"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-6 box-col-12">
			<div class="card">
				<div class="card-header">
					<h5>Leads Based on Gender</h5>
				</div>
				<div class="card-body p-3
                ">
					<div class="apache-container" id="echart-pieric
                    h"></div>
				</div>
			</div>
		</div>
        {{-- <div class="col-xl-12">
			<div class="card">
				<div class="card-header">
					<h5>Leads Based on Average Earnings</h5>
				</div>
				<div class="card-body">
					<div class="apache-cotainer" id="dynamic-data"></div>
				</div>
			</div>
		</div> --}}
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Leads Based on Domicili</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="show-case">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Domicili</th>
                                    <th>Total</th>
                                    <th>percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Jakarta Pusat</td>
                                    <td>30</td>
                                    <td>20%</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jakarta Timur</td>
                                    <td>80</td>

                                    <td>30%</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Bekasi</td>
                                    <td>120</td>
                                    <td>50%</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Domicili</th>
                                    <th>Total</th>
                                    <th>percentage</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/esl.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/config.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/pie-chart/facePrint.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/pie-chart/testHelper.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/pie-chart/custom-transition-texture.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/data/symbols.js')}}"></script>
{{-- <script src="{{asset('assets/js/chart/echart/custom.js')}}"></script> --}}
<script>
    // pie chart js
require(
    (testHelper.hasURLParam('en')
        ? [
            'echarts',
            // 'echarts/lang/en',
        ]
        : [
            'echarts'
        ]
    ).concat(
        [
            // 'echarts/chart/bar',
            // 'echarts/chart/line',
            // 'echarts/component/legend',
            // 'echarts/component/graphic',
            // 'echarts/component/grid',
            // 'echarts/component/tooltip',
            // 'echarts/component/brush',
            // 'echarts/component/toolbox',
            // 'echarts/component/title',
            // 'zrender/vml/vml'
        ]
    ),
    function (echarts) {

        var chart = echarts.init(document.getElementById('echart-pie'));

        chart.setOption({
            aria: {
                enabled: true
            },
            title : {
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: {!!JSON_encode($categoryAge)!!}
            },
            series : [
                {
                    name: 'Visit source',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    selectedMode: 'single',
                    data:{!!JSON_encode($leadsByAge)!!},
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        });

        chart.on('pieselectchanged', function (e) {
            console.log(e);
        });

        window.onresize = chart.resize;
    }
);

// pie rich chart js
require(['echarts'/*, 'map/js/china' */], function (echarts) {
        var option;
        // $.getJSON('./data/nutrients.json', function (data) {});
        var colorList = [
        {
            type: 'linear',
            x: 1,
            y: 0,
            x2: 0,
            y2: 1,
            colorStops: [{
                    offset: 0,
                    color: 'rgba(115,172,255,0.02)' // 0% 处的颜色
                },
                {
                    offset: 1,
                    color: 'rgba(115,172,255,0.67)' // 100% 处的颜色
                }
            ],
            globalCoord: false // 缺省为 false
        },
        {
            type: 'linear',
            x: 0,
            y: 1,
            x2: 0,
            y2: 0,
            colorStops: [{
                    offset: 0,
                    color: 'rgba(252,75,75,0.01)' // 0% 处的颜色
                },
                {
                    offset: 1,
                    color: 'rgba(189,75,75,0.57)' // 100% 处的颜色
                }
            ],
            globalCoord: false // 缺省为 false
        }
    ]
    var colorLine = ['#73ACFF', '#FE6969']

    function getRich() {
        let result = {}
        colorLine.forEach((v, i) => {
            result[`hr${i}`] = {
                backgroundColor: colorLine[i],
                borderRadius: 3,
                width: 3,
                height: 3,
                padding: [0, 3, 3, -12]
            }
            result[`a${i}`] = {
                padding: [-20, -60, 0, -20],
                color: colorLine[i],
                fontSize: 12
            }
        })
        return result
    }
    let data = {!!JSON_encode($leadsByGender)!!}.sort((a, b) => {
        return b.value - a.value
    })

    data.forEach((v, i) => {
        v.labelLine = {
            lineStyle: {
                width: 1,
                color: colorLine[i]
            }
        }
    })
    option = {
        series: [{
            type: 'pie',
            radius: '60%',
            center: ['50%', '50%'],
            clockwise: true,
            avoidLabelOverlap: true,
            label: {
                show: true,
                position: 'outside',
                formatter: function(params) {
                    const name = params.name
                    const percent = params.percent + '%'
                    const index = params.dataIndex
                    return [`{a${index}|${name}：${percent}}`, `{hr${index}|}`].join('\n')
                },
                rich: getRich()
            },
            itemStyle: {
                normal: {
                    color: function(params) {
                        return colorList[params.dataIndex]
                    }
                }
            },
            data,
            roseType: 'radius'
        }]
    }

        var chart = testHelper.create(echarts, 'echart-pierich', {
            option: option
            // height: 300,
            // buttons: [{text: 'btn-txt', onclick: function () {}}],
            // recordCanvas: true,
        });
});

// pictorial repeat chart js

function makeChart(id, option, cb) {
    require([
        'echarts'
        // 'echarts/chart/pictorialBar',
        // 'echarts/chart/bar',
        // 'echarts/chart/scatter',
        // 'echarts/component/grid',
        // 'echarts/component/markLine',
        // 'echarts/component/legend',
        // 'echarts/component/tooltip',
        // 'echarts/component/dataZoom'
    ], function (echarts) {
        var main = document.getElementById(id);
        if (main) {
            var chartMain = document.createElement('div');
            chartMain.style.cssText = 'height:100%';
            main.appendChild(chartMain);
            var chart = echarts.init(chartMain);
            chart.setOption(option);

            window.addEventListener('resize', chart.resize);

            cb && cb(echarts, chart);
        }

    });
}

var startData = 13000;
var maxData = 18000;
var minData = 5000;

makeChart('dynamic-data', {
    backgroundColor: '#f8f8f8',
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'none',
            label: {show: true}
        }
    },
    legend: {
        data: ['all'],
        textStyle: {color: '#2b2b2b'}
    },
    grid: {
        bottom: 100
    },
    xAxis: [{
        data: [
            'standard',
            'fix symbol margin\n(not accurate)\n(but more comparable)',
            'use symbolBoundingData\nauto repeat times\n(accurate)\n(but symbolMargin not fixed)',
            'use symbolBoundingData\nfix repeat times\n(accurate)\n(but less responsive)'
        ],
        axisTick: {show: false},
        axisLine: {
            lineStyle: {
                color: '#ddd'
            }
        },
        axisLabel: {
            margin: 20,
            interval: 0,
            textStyle: {
                color: '#2b2b2b',
                fontSize: 14
            }
        }
    }],
    yAxis: {
        splitLine: {show: false},
        axisTick: {
            lineStyle: {
                color: '#ddd'
            }
        },
        axisLine: {
            lineStyle: {
                color: '#ddd'
            }
        },
        axisLabel: {
            textStyle: {
                color: '#2b2b2b'
            }
        }
    },
    animationEasing: 'cubicOut',
    animationDuration: 100,
    animationDurationUpdate: 2000,
    series: [{
        type: 'pictorialBar',
        name: 'all',
        id: 'paper',
        hoverAnimation: true,
        label: {
            normal: {
                show: true,
                position: 'top',
                formatter: '{c} km',
                textStyle: {
                    fontSize: 16,
                    color: '#2b2b2b'
                }
            }
        },
        symbol: imageSymbols.paper,
        symbolSize: ['70%', 50],
        symbolMargin: '-25%',
        data: [{
            value: maxData,
            symbolRepeat: true
        }, {
            value: startData,
            symbolRepeat: true
        }, {
            value: startData,
            symbolBoundingData: startData,
            symbolRepeat: true
        }, {
            value: startData,
            symbolBoundingData: startData,
            symbolRepeat: 20
        }],
        markLine: {
            symbol: ['none', 'none'],
            label: {
                normal: {show: false}
            },
            lineStyle: {
                normal: {
                    color: '#e54035'
                }
            },
            data: [{
                yAxis: startData
            }]
        }
    }, {
        name: 'all',
        type: 'pictorialBar',
        symbol: 'circle',
        itemStyle: {
            normal: {
                color: '#ffffff'
            }
        },
        silent: true,
        symbolSize: ['150%', 50],
        symbolOffset: [0, 20],
        z: -10,
        data: [1, 1, 1, 1]
    }]
}, function (echarts, chart) {

    setInterval(function () {
        var dynamicData = Math.round(Math.random() * (maxData - minData) + minData);

        chart.setOption({
            series: [{
                id: 'paper',
                data: [{
                    value: maxData,
                    symbolRepeat: true
                }, {
                    value: dynamicData,
                    symbolRepeat: true
                }, {
                    value: dynamicData,
                    symbolBoundingData: dynamicData,
                    symbolRepeat: true
                }, {
                    value: dynamicData,
                    symbolBoundingData: dynamicData,
                    symbolRepeat: 20
                }],
                markLine: {
                    data: [{
                        yAxis: dynamicData
                    }]
                }
            }]
        });
    }, 3000);
});
</script>
@endsection
