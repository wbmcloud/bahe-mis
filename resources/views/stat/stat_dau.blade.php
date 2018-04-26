@extends('admin_template')
@section('head')
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/iCheck/square/blue.css") }}">
@endsection

@section('content')
    <section class="content">
        <div class="box-body">
            <div class="form-group">
                <label>城市选择</label>
                <select class="city_multi form-control select2" id="city_id" name="city_id"
                        onchange="changeCity()" required>
                    @foreach($cities as $city)
                        <option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>游戏选择&nbsp;</label>
                <input type="radio" value="1" name="game_type" checked>&nbsp;&nbsp;&nbsp;麻将&nbsp;&nbsp;
                <input type="radio" value="2" name="game_type">&nbsp;&nbsp;&nbsp;斗地主
            </div>

        </div>
        <div id="main" style="width: 100%;height:500px;">
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/dist/js/echarts.min.js") }}"></script>
    <script>
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));

        option = {
            title: {
                text: 'DAU统计'
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
                data:['总局数']
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

        function changeCity()
        {
            var _city_id = $('select[name="city_id"] option:selected').val();
            var _game_type = $('input[name="game_type"]:checked').val();
            fillIn(myChart, option, _city_id, _game_type);
        }

        function fillIn(chart, option, city_id, game_type)
        {
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/stat/dau",
                data: {
                    city_id: city_id,
                    game_type: game_type
                },
                method: 'GET',
                success: function (res) {
                    if (res.code) {
                        return;
                    }
                    var _chart_data = [];
                    var _day_axis = [];
                    var _total_rounds = {
                        'name': '日活',
                        'type': 'line',
                        'data': []
                    };
                    var _data = res.data['list'];
                    for (var i = 0; i < _data.length; i++) {
                        _day_axis[i] = _data[i]['day'],
                            _total_rounds['data'][i] = _data[i]['amount'];
                    }

                    _chart_data.push(_total_rounds);

                    option['xAxis'][0]['data'] = _day_axis;
                    option['series'] = _chart_data;

                    // 使用刚指定的配置项和数据显示图表。
                    myChart.setOption(option);
                }
            });
        }

        $('input[name="game_type"]').on('ifChecked', function () {
            changeCity();
        });

        $(document).ready(function () {
            $(".city_multi").select2({
                placeholder: "请选择开通城市",
                maximumSelectionLength: 3,
                tags: true
            });

            $("input").iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            changeCity();

            $('#stat').addClass('active');
            $('#stat_dau').addClass('active');
        });
    </script>
@endsection