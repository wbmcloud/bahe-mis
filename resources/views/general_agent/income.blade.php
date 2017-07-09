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
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">本周总金额</label>
                        <p>{{ $income_stat['sale_amount'] }}</p>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">代理提成</label>
                        <p>{{ $income_stat['sale_commission'] }}</p>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">个人代理销售金额</label>
                        <p>{{ $income_stat['agent_sale_amount'] }}</p>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">上周收入</label>
                        <p>{{ $income_stat['last_week_income'] }}</p>
                    </div>
                    <a href="{{ route('general_agent.sale') }}"><button class="btn btn-info">本周明细查询</button></a>
                    <a href="{{ route('general_agent.income_history') }}"><button class="btn btn-info">历史记录查询</button></a>
                </div>
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