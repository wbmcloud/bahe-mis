@extends('admin_template')
@section('head')
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/morris/morris.css") }}">
@endsection
@section('content')
    <section class="content-header">
        <h1>
            代理统计
        </h1>
    </section>
    <section class="content">
        <div class="col-md-6">
            <!-- AREA CHART -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">充值流水</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="revenue-chart" style="height: 300px;"></div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </section>
@endsection

@section('script')
    <!-- Morris.js charts -->
    <script src="https://cdn.bootcss.com/raphael/2.2.7/raphael.min.js"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/morris/morris.min.js") }}"></script>
    <script>
        $(function () {
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/stat/flow",
                method: 'GET',
                success: function (res) {
                    var _chart_data = [];
                    var _data = res.data['list'];
                    for (var i = 0; i < _data.length; i++) {
                        _chart_data.push({
                            'y': _data[i]['day'],
                            'agent_recharge': _data[i]['agent_recharge_card_total'],
                            'user_recharge': _data[i]['user_recharge_card_total'],
                            'open_room': _data[i]['open_room_card_total']
                        });
                    }
                    // AREA CHART
                    var area = new Morris.Area({
                        element: 'revenue-chart',
                        resize: true,
                        data: _chart_data,
                        xkey: 'y',
                        ykeys: ['agent_recharge', 'user_recharge', 'open_room'],
                        labels: ['代理充值', '用户充值', '代开房'],
                        lineColors: ['#a0d0e0', '#3c8dbc', '#3c8dae'],
                        hideHover: 'auto'
                    });
                }
            });
        });


        $('#stat').addClass('active');
        $('#stat_flow').addClass('active');
    </script>
@endsection