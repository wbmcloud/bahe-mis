@extends('admin_template')
@section('head')
    <style>
        .pagination {
            margin-left: 40%;
        }
        h4 {
            text-align: center;
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
                <h4>本周收入统计</h4>
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
                            <td>总监销售金额</td>
                            <td>{{ $income_stat['first_agent_sale_amount'] }}</td>
                        </tr>
                        <tr>
                            <td>总监提成</td>
                            <td>{{ $income_stat['first_agent_sale_commission'] }}</td>
                        </tr>
                        <tr>
                            <td>代理销售金额</td>
                            <td>{{ $income_stat['agent_sale_amount'] }}</td>
                        </tr>
                        <tr>
                            <td>代理提成</td>
                            <td>{{ $income_stat['agent_sale_commission'] }}</td>
                        </tr>
                        <tr>
                            <td>个人代理销售金额</td>
                            <td>{{ $income_stat['general_agent_sale_amount'] }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <h4>本周代理销售账单明细</h4>
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