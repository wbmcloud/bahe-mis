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
            本周账单明细
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="agent_container" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>代理姓名</th>
                                <th>销售金额（单位：元）</th>
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
        $('#sale').addClass('active');
    </script>
@endsection