@extends('admin_template')
@section('head')
    <style>
        .pagination {
            margin-left: 40%;
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            本周账单查询
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
                    <!-- /input-group -->
                    <!-- /.box-header -->
                    <div class="box">
                        <div class="box-body">
                            <table id="agent_container" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>销售量</th>
                                </tr>
                                </thead>
                                <tbody id="agent_list_container">
                                @if(empty($level_agent_sale_amount_list->total()))
                                    <tr>
                                        <td colspan="2">没有记录</td>
                                    </tr>
                                @else
                                    @foreach($level_agent_sale_amount_list as $level_agent_sale_amount)
                                        <tr>
                                            <td>{{ $agents[$level_agent_sale_amount['user_id']]['name'] }}</td>
                                            <td>{{ $level_agent_sale_amount['sum'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            {{ $level_agent_sale_amount_list->links() }}
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
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
    <script src="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
    <script>
        $('#data_stat').addClass('active');
        $('#income').addClass('active');
    </script>
@endsection