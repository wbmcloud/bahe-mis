@extends('admin_template')
@section('head')
    <style>
        .pagination {
            margin-left: 40%;
        }
    </style>
    <!-- daterange picker -->
    <link rel="stylesheet"
          href="{{ asset("/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.css") }}">

@endsection
@section('content')
    <section class="content-header">
        <h1>
            收入统计
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>统计类型</th>
                            <th>金额（单位：元）</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>本周销售总收入</td>
                            <td>{{ $income_stat['current_week_income'] }}</td>
                        </tr>
                        <tr>
                            <td>本周发展代理充值总金额</td>
                            <td>{{ $income_stat['first_agent_sale_amount'] }}</td>
                        </tr>
                        <tr>
                            <td>本周发展代理收入</td>
                            <td>{{ $income_stat['first_agent_sale_commission'] }}</td>
                        </tr>
                        <tr>
                            <td>本周个人代理销售金额</td>
                            <td>{{ $income_stat['agent_sale_amount'] }}</td>
                        </tr>
                        <tr>
                            <td>上周销售总收入</td>
                            <td>{{ $income_stat['last_week_income'] }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('first_agent.sale') }}"><button class="btn btn-info">本周明细查询</button></a>
                <a href="{{ route('first_agent.income_history') }}"><button class="btn btn-info">历史收入查询</button></a>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    </div>
@endsection
@section('script')
    <script>
        $('#data_stat').addClass('active');
        $('#income').addClass('active');
    </script>
@endsection