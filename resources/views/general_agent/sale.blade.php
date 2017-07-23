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
                                        <td>{{ $agents[$level_agent_sale_amount['id']]['name'] }}</td>
                                        <td>{{ $level_agent_sale_amount['sum'] * \App\Common\Constants::ROOM_CARD_PRICE }}</td>
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
        function getRequest() {
            var url = location.search; //获取url中"?"符后的字串
            var request_arr = new Object();
            if (url.indexOf("?") != -1) {
                var str = url.substr(1);
                strs = str.split("&");
                for (var i = 0; i < strs.length; i++) {
                    request_arr[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
                }
            }
            return request_arr;
        }

        $('#data_stat').addClass('active');
        var _params = getRequest();
        if (_params['type'] == 3) {
            $('#agent_sale').addClass('active');
        } else if (_params['type'] == 4) {
            $('#first_agent_sale').addClass('active');
        }
    </script>
@endsection