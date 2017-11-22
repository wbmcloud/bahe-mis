@extends('admin_template')
@section('head')
    <style>
    .pagination {
        margin-left: 40%;
    }
    </style>
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.css") }}">

@endsection
@section('content')
    <section class="content-header">
        <h1>
            消费记录
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--div class="box-header">
                        <h3 class="box-title">Hover Data Table</h3>
                    </div-->
                    <!-- Date range -->
                    <div class="input-group margin" style="width:80%;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="reservation">
                        <span class="input-group-btn">
                              <button type="button" class="btn btn-info btn-flat" onclick="query();">查询</button>
                        </span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="agent_container" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                            <th>发起人id</th>
                            <th>发起人用户名</th>
                            <th>发起人类型</th>
                            <th>接收人id</th>
                            <th>接收人用户名</th>
                            <th>接收人类型</th>
                            <th>交易类型</th>
                            <th>充值数量</th>
                            <th>赠送数量</th>
                            <th>交易时间</th>
                            <th>交易是否成功</th>
                            <th>失败原因</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($recharge_list->count()))
                                <tr><td colspan="12">没有记录</td></tr>
                            @else
                                @foreach($recharge_list as $recharge)
                                    <tr>
                                        <td>{{ $recharge['initiator_id'] }}</td>
                                        <td>{{ $recharge['initiator_name'] }}</td>
                                        <td>{{ \App\Common\Constants::$role_type[$recharge['initiator_type']] }}</td>
                                        <td>{{ $recharge['recipient_id'] }}</td>
                                        <td>{{ $recharge['recipient_name'] }}</td>
                                        <td>{{ \App\Common\Constants::$role_type[$recharge['recipient_type']] }}</td>
                                        <td>{{ \App\Common\Constants::$transaction_type[$recharge['recharge_type']] }}</td>
                                        <td>{{ $recharge['num'] }}</td>
                                        <td>{{ $recharge['give_num'] }}</td>
                                        <td>{{ $recharge['created_at'] }}</td>
                                        <td>{{ \App\Common\Constants::$recharge_status[$recharge['status']] }}</td>
                                        <td>{{ \App\Exceptions\BaheException::getErrorMsg($recharge['recharge_fail_reason']) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $recharge_list->appends([
                            'start_date' => \Illuminate\Support\Facades\Request::input('start_date'),
                            'end_date' => \Illuminate\Support\Facades\Request::input('end_date'),
                            'id' => \Illuminate\Support\Facades\Request::input('id'),
                        ])->links() }}
                    </div>
                    <!-- /.box-body -->
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
@section('ui')
    <div class="modal_container modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
                    <h4 class="modal-title" id="gridSystemModalLabel">提示</h4>
                </div>
                <div class="modal-body">
                    <span id="msg"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm" class="btn btn-default">确认</button>
                    <!--button type="button" class="btn btn-primary">保存</button-->
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection
@section('script')
    <script src="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.js") }}"></script>

    <script>
        function getCurrenturl()
        {
            return location.origin + location.pathname;
        }

        function getUrlParams() {
            var _str=location.href; //取得整个地址栏
            var _num=_str.indexOf("?")
            _str = _str.substr(_num + 1);
            return _str;
        }

        function getRequest() {
            var url = location.search; //获取url中"?"符后的字串
            var request_arr = new Object();
            if (url.indexOf("?") != -1) {
                var str = url.substr(1);
                strs = str.split("&");
                for(var i = 0; i < strs.length; i ++) {
                    request_arr[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
                }
            }
            return request_arr;
        }

        function query()
        {
            var _date_range = $('#reservation').val();
            var _date_arr = _date_range.split(' - ');
            var _args = getRequest();
            _args['start_date'] = _date_arr[0];
            _args['end_date'] = _date_arr[1];
            location.href = getCurrenturl() + '?' + $.param(_args);
        }

        $(document).ready(function () {
            //Date range picker
            $('#reservation').daterangepicker({
                'locale': {
                    "format": "YYYY-MM-DD",
                },
                "startDate": getRequest()['start_date'],
                "endDate": getRequest()['end_date']
            }, function (start, end, label) {

            });
        });
        $('#agent_consume_flow').addClass('active');
        $('#agent').addClass('active');
        $('#agent_list').addClass('active');
    </script>
@endsection