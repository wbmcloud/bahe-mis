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
            角色列表
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="input-group margin" style="width:80%;">
                        <input id="query_str" type="text" class="col-sm-2 form-control" placeholder="请输入角色id或者账号">
                        <span class="input-group-btn">
                              <button type="button" class="btn btn-info btn-flat" onclick="query();">搜索</button>
                        </span>
                    </div>
                    <!-- /input-group -->

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="agent_container" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                            <th>id</th>
                            <th>角色id</th>
                            <th>角色名</th>
                            <th>服务器id</th>
                            <th>账号</th>
                            <th>剩余房卡数</th>
                            <th>创建时间</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($players->count()))
                                <tr><td colspan="6">没有记录</td></tr>
                            @else
                                @foreach($players as $player)
                                    <tr>
                                        <td>{{ $player['id'] }}</td>
                                        <td>{{ $player['player_id'] }}</td>
                                        <td>{{ $player['player_name'] }}</td>
                                        <td>{{ $player['server_id'] }}</td>
                                        <td>{{ $player['user_name'] }}</td>
                                        <td>{{ $player['card_balance'] }}</td>
                                        <td>{{ $player['create_time'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $players->links() }}
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
                $('#msg').html("请输入角色id或者角色名");
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

        $('#game').addClass('active');
        $('#player_list').addClass('active');
    </script>
@endsection