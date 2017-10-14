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
                    text: '房卡数统计'
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
                    data:['代理充值房卡数','用户充值房卡数','代开房消耗房卡数']
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
                url: "/api/stat/flow",
                method: 'GET',
                success: function (res) {
                    var _chart_data = [];
                    var _day_axis = [];
                    var _agent_recharge = {
                        'name': '代理充值房卡数',
                        'type': 'line',
                        'stack': '总量',
                        'areaStyle': {normal: {}},
                        'data': []
                    };
                    var _user_recharge = {
                        'name': '用户充值房卡数',
                        'type': 'line',
                        'stack': '总量',
                        'areaStyle': {normal: {}},
                        'data': []
                    };
                    var _open_room = {
                        'name': '代开房消耗房卡数',
                        'type': 'line',
                        'stack': '总量',
                        'areaStyle': {normal: {}},
                        'data': []
                    };
                    var _data = res.data['list'];
                    for (var i = 0; i < _data.length; i++) {
                        _day_axis[i] = _data[i]['day'],
                            _agent_recharge['data'][i] = _data[i]['agent_recharge_card_total'],
                            _user_recharge['data'][i] = _data[i]['user_recharge_card_total'],
                            _open_room['data'][i] = _data[i]['open_room_card_total']
                    }

                    _chart_data.push(_agent_recharge);
                    _chart_data.push(_user_recharge);
                    _chart_data.push(_open_room);

                    option['xAxis'][0]['data'] = _day_axis;
                    option['series'] = _chart_data;

                    // 使用刚指定的配置项和数据显示图表。
                    myChart.setOption(option);
                }
            });

            $('#stat').addClass('active');
            $('#stat_flow').addClass('active');
        });
    </script>
@endsection