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
                    <h3 class="box-title">新增代理</h3>

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
                url: "/api/stat/agent",
                method: 'GET',
                success: function (res) {
                    var _chart_data = [];
                    var _data = res.data['list'];
                    for (var i = 0; i < _data.length; i++) {
                        _chart_data.push({
                            'y': _data[i]['day'],
                            'agent': _data[i]['agent_add_total'],
                            'first_agent': _data[i]['first_agent_add_total'],
                            'general_agent': _data[i]['general_agent_add_total']
                        });
                    }
                    // AREA CHART
                    var area = new Morris.Area({
                        element: 'revenue-chart',
                        resize: true,
                        data: _chart_data,
                        xkey: 'y',
                        ykeys: ['agent', 'first_agent', 'general_agent'],
                        labels: ['代理', '总代理', '总监'],
                        lineColors: ['#a0d0e0', '#3c8dbc', '#3c8dae'],
                        hideHover: 'auto'
                    });
                }
            });
        });

        $('#stat').addClass('active');
        $('#stat_agent').addClass('active');
    </script>
@endsection