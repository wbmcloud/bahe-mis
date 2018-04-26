@extends('admin_template')

@section('head')
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
@endsection

@section('content')
    <section class="content">
        <div class="box-body">
            <div class="form-group">
                <label for="city_id">城市选择</label>
                <select class="city_multi form-control select2" id="city_id" name="city_id"
                        onchange="changeCity(this.selectedOptions[0])" required>
                    @foreach($cities as $city)
                        <option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="main" style="width: 100%;height:500px;">
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/dist/js/echarts.min.js") }}"></script>
    <script>
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));

        option = {
            title: {
                text: '代理充值数统计'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross',
                    label: {
                        backgroundColor: '#6a7985'
                    }
                }
            },
            legend: {
                data: ['用户充值房卡数', '代开房消耗房卡数']
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
            xAxis: [
                {
                    type: 'category',
                    boundaryGap: false,
                    data: []
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: []
        };

        function changeCity(e)
        {
            var _city_id = $(e).val();
            fillIn(myChart, option, _city_id);
        }

        function fillIn(chart, option, city_id)
        {
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/stat/agent_flow",
                data: {
                    city_id: city_id,
                },
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
                    var _data = res.data['list'];
                    for (var i = 0; i < _data.length; i++) {
                        _day_axis[i] = _data[i]['day'],
                            _agent_recharge['data'][i] = _data[i]['total'];
                    }

                    _chart_data.push(_agent_recharge);

                    option['xAxis'][0]['data'] = _day_axis;
                    option['series'] = _chart_data;

                    // 使用刚指定的配置项和数据显示图表。
                    myChart.setOption(option);
                }
            });
        }

        $(document).ready(function () {
            $(".city_multi").select2({
                placeholder: "请选择开通城市",
                maximumSelectionLength: 3,
                tags: true
            });

            changeCity($('#city_id'));

            $('#stat').addClass('active');
            $('#stat_agent_flow').addClass('active');
        });

    </script>
@endsection