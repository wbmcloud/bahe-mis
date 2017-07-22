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
            总监收入记录
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="input-group margin" style="width:40%;">
                        <input id="query_str" type="text" class="col-sm-2 form-control" placeholder="请输入姓名">
                        <span class="input-group-btn">
                              <button type="button" class="btn btn-info btn-flat" onclick="query();">搜索</button>
                        </span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="agent_container" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>姓名</th>
                                <th>绑定邀请码</th>
                                <th>被邀邀请码</th>
                                <th>收入额度</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($first_agents->total()))
                                <tr>
                                    <td colspan="6">没有记录</td>
                                </tr>
                            @else
                                @foreach($first_agents as $first_agent)
                                    <tr>
                                        <td>{{ $first_agent['id'] }}</td>
                                        <td>{{ $first_agent['name'] }}</td>
                                        <td>{{ $first_agent['code'] }}</td>
                                        <td>{{ $first_agent['invite_code'] }}</td>
                                        <td>{{ $first_agent['total_income'] }}</td>
                                        <td>
                                            <button type="button" onclick="rechargeList('{{ route('general_agent.rechargelist', ['invite_code' => $first_agent['code']]) }}')"
                                                    class="btn btn-primary">代理充值记录
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $first_agents->appends([
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

    <script>

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

        function query()
        {
            var query_str = $("#query_str").val();
            if (!query_str) {
                $('#msg').html("请输入姓名");
                $("#confirm").attr("data-dismiss", "modal");
                $("#confirm").removeAttr("onclick");
                $('.modal_container').modal({
                    "show": true,
                    "backdrop": false,
                    "keyboard": false
                });
            } else {
                var _args = getRequest();
                _args['query_str'] = query_str;
                location.href = getCurrenturl() + '?' + $.param(_args);
            }
        }

        function rechargeList(url) {
            location.href = url;
        }
    </script>
@endsection