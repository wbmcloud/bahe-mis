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
            充值信息
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
                    <div class="input-group margin" style="width:50%;">
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
                                <th>代理id</th>
                                <th>代理用户名</th>
                                <th>充值类型</th>
                                <th>充值额度</th>
                                <th>充值时间</th>
                                @role(['super', 'admin'])
                                <th>操作</th>
                                @endrole
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($recharge_flows->total()))
                                <tr>
                                    <td colspan="4">没有记录</td>
                                </tr>
                            @else
                                @foreach($recharge_flows as $recharge_flow)
                                    <tr>
                                        <td>{{ $recharge_flow['recipient_id'] }}</td>
                                        <td>{{ $recharge_flow['recipient_name'] }}</td>
                                        <td>{{ \App\Common\Constants::$transaction_type[$recharge_flow['recharge_type']] }}</td>
                                        <td>{{ $recharge_flow['num'] }}</td>
                                        <td>{{ $recharge_flow['created_at'] }}</td>
                                        @role(['super', 'admin'])
                                        <td>
                                            <button type="button"
                                                    onclick="delRechargeRecord({{ $recharge_flow['id'] }})"
                                                    class="btn btn-primary">删除
                                            </button>
                                        </td>
                                        @endrole
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $recharge_flows->appends([
                            'start_date' => \Illuminate\Support\Facades\Request::input('start_date'),
                            'end_date' => \Illuminate\Support\Facades\Request::input('end_date'),
                            'invite_code' => \Illuminate\Support\Facades\Request::input('invite_code'),
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
        function delRechargeRecord(id) {
            var data = {
                'id': id,
            }
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/general_agent/delflow",
                data: data,
                success: function (res) {
                    if (!res.code) {
                        $("#confirm").removeAttr("data-dismiss");
                        $("#confirm").attr("onclick", "hide()");
                    } else {
                        $("#confirm").attr("data-dismiss", "modal");
                        $("#confirm").removeAttr("onclick");
                    }
                    $('#msg').html(res.msg);
                    $('.modal_container').modal({
                        "show": true,
                        "backdrop": false,
                        "keyboard": false
                    });
                }
            });

        };

        function hide() {
            $(".modal_container").modal('hide');
            location.reload();
        }

        function getCurrenturl() {
            return location.origin + location.pathname;
        }

        function getUrlParams() {
            var _str = location.href; //取得整个地址栏
            var _num = _str.indexOf("?")
            _str = _str.substr(_num + 1);
            return _str;
        }

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

        function query() {
            var _date_range = $('#reservation').val();
            var _date_arr = _date_range.split(' - ');
            var _args = getRequest();
            _args['start_date'] = _date_arr[0];
            _args['end_date'] = _date_arr[1];
            location.href = getCurrenturl() + '?' + $.param(_args);
        }


        $(document).ready(function () {
            console.log(getRequest());
            //Date range picker
            $('#reservation').daterangepicker({
                'locale': {
                    "format": "YYYY-MM-DD",
                },
                "startDate": getRequest()['start_date'],
                "endDate": getRequest()['end_date']
            }, function (start, end, label) {
                /*var _date_range = '"' + start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD') + '"';
                 console.log(_date_range);
                 $('#reservation').val(_date_range);*/
            });
        });
    </script>
@endsection