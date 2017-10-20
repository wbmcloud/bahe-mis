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
            用户充值记录
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--div class="box-header">
                        <h3 class="box-title">Hover Data Table</h3>
                    </div-->
                <div class="input-group margin" style="width:80%;">
                    <input id="query_str" type="text" class="col-sm-2 form-control" placeholder="请输入玩家角色id">
                    <span class="input-group-btn">
                          <button type="button" class="btn btn-info btn-flat" onclick="query();">查询</button>
                    </span>
                </div>
                <!-- /.box-header -->
                    <div class="box-body">
                        <table id="agent_container" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>发起人id</th>
                                <th>发起人用户名</th>
                                <th>发起人类型</th>
                                <th>接收人id</th>
                                <th>接收人用户名</th>
                                <th>接收人类型</th>
                                <th>交易类型</th>
                                <th>数量</th>
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
                                        <td>{{ $recharge['id'] }}</td>
                                        <td>{{ $recharge['initiator_id'] }}</td>
                                        <td>{{ $recharge['initiator_name'] }}</td>
                                        <td>{{ \App\Common\Constants::$role_type[$recharge['initiator_type']] }}</td>
                                        <td>{{ $recharge['recipient_id'] }}</td>
                                        <td>{{ $recharge['recipient_name'] }}</td>
                                        <td>{{ \App\Common\Constants::$role_type[$recharge['recipient_type']] }}</td>
                                        <td>{{ \App\Common\Constants::$transaction_type[$recharge['recharge_type']] }}</td>
                                        <td>{{ $recharge['num'] }}</td>
                                        <td>{{ $recharge['created_at'] }}</td>
                                        <td>{{ \App\Common\Constants::$recharge_status[$recharge['status']] }}</td>
                                        <td>{{ \App\Exceptions\BaheException::getErrorMsg($recharge['recharge_fail_reason']) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $recharge_list->appends([
                            'query_str' => \Illuminate\Support\Facades\Request::input('query_str'),
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
        function getCurrenturl()
        {
            return location.origin + location.pathname;
        }

        function query()
        {
            var query_str = $("#query_str").val();
            if (!query_str) {
                $('#msg').html("请输入玩家角色id");
                $("#confirm").attr("data-dismiss", "modal");
                $("#confirm").removeAttr("onclick");
                $('.modal_container').modal({
                    "show": true,
                    "backdrop": false,
                    "keyboard": false
                });
            } else {
                location.href = getCurrenturl() + '?query_str=' + query_str;
            }
        }

        $('#record').addClass('active');
        $('#user_recharge_record').addClass('active');
    </script>
@endsection