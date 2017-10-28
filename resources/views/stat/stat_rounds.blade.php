@extends('admin_template')
@section('content')
    <section class="content">
        <div id="main" style="width: 100%;height:500px;">
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset("/bower_components/admin-lte/dist/js/echarts.min.js") }}"></script>
    <script>
        $(function () {

            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('main'));

            option = {
                title: {
                    text: '局数统计'
                },
                tooltip : {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'cross',
                        label: {
                            backgroundColor: '#6a7985'
                        }
                    }
                },
                legend: {
                    data:['总局数','16桌局数','8桌局数']
                },
                toolbox: {
                    feature: {
                        saveAsImage: {}
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap : false,
                        data : []
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : []
            };
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/stat/rounds",
                method: 'GET',
                success: function (res) {
                    var _chart_data = [];
                    var _day_axis = [];
                    var _total_rounds = {
                        'name': '总局数',
                        'type': 'line',
                        'stack': '总量',
                        'areaStyle': {normal: {}},
                        'data': []
                    };
                    var _sixteen_rounds = {
                        'name': '16桌局数',
                        'type': 'line',
                        'stack': '总量',
                        'areaStyle': {normal: {}},
                        'data': []
                    };
                    var _eight_rounds = {
                        'name': '8桌局数',
                        'type': 'line',
                        'stack': '总量',
                        'areaStyle': {normal: {}},
                        'data': []
                    };
                    var _data = res.data['list'];
                    for (var i = 0; i < _data.length; i++) {
                        _day_axis[i] = _data[i]['day'],
                            _total_rounds['data'][i] = _data[i]['total_rounds'],
                            _sixteen_rounds['data'][i] = _data[i]['sixteen_rounds'],
                            _eight_rounds['data'][i] = _data[i]['eight_rounds']
                    }

                    _chart_data.push(_total_rounds);
                    _chart_data.push(_sixteen_rounds);
                    _chart_data.push(_eight_rounds);

                    option['xAxis'][0]['data'] = _day_axis;
                    option['series'] = _chart_data;

                    // 使用刚指定的配置项和数据显示图表。
                    myChart.setOption(option);
                }
            });

            $('#stat').addClass('active');
            $('#stat_rounds').addClass('active');
        });
    </script>
@endsection