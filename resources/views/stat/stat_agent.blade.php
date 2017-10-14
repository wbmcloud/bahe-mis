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
                    text: '新增各级代理统计'
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
                    data:['新增代理','新增总代理','新增总监']
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
                url: "/api/stat/agent",
                method: 'GET',
                success: function (res) {
                    var _chart_data = [];
                    var _day_axis = [];
                    var _agent = {
                        'name': '新增代理',
                        'type': 'line',
                        'stack': '总量',
                        'areaStyle': {normal: {}},
                        'data': []
                    };
                    var _first_agent = {
                        'name': '新增总代理',
                        'type': 'line',
                        'stack': '总量',
                        'areaStyle': {normal: {}},
                        'data': []
                    };
                    var _general_agent = {
                        'name': '新增总监',
                        'type': 'line',
                        'stack': '总量',
                        'areaStyle': {normal: {}},
                        'data': []
                    };
                    var _data = res.data['list'];
                    for (var i = 0; i < _data.length; i++) {
                        _day_axis[i] = _data[i]['day'],
                        _agent['data'][i] = _data[i]['agent_add_total'],
                        _first_agent['data'][i] = _data[i]['first_agent_add_total'],
                        _general_agent['data'][i] = _data[i]['general_agent_add_total']
                    }

                    _chart_data.push(_agent);
                    _chart_data.push(_first_agent);
                    _chart_data.push(_general_agent);

                    option['xAxis'][0]['data'] = _day_axis;
                    option['series'] = _chart_data;

                    // 使用刚指定的配置项和数据显示图表。
                    myChart.setOption(option);
                }
            });

            $('#stat').addClass('active');
            $('#stat_agent').addClass('active');
        });

    </script>
@endsection