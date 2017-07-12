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
            一级代理收入统计
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
                            <th>数量</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>本周总金额</td>
                            <td>{{ $income_stat['sale_amount'] }}</td>
                        </tr>
                        <tr>
                            <td>代理提成</td>
                            <td>{{ $income_stat['sale_commission'] }}</td>
                        </tr>
                        <tr>
                            <td>个人代理销售金额</td>
                            <td>{{ $income_stat['agent_sale_amount'] }}</td>
                        </tr>
                        <tr>
                            <td>上周收入</td>
                            <td>{{ $income_stat['last_week_income'] }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                
                <a href="{{ route('general_agent.sale') }}"><button class="btn btn-info">本周明细查询</button></a>
                <a href="{{ route('general_agent.income_history') }}"><button class="btn btn-info">历史记录查询</button></a>
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