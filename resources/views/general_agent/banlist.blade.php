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
            封禁代理人列表
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--div class="box-header">
                        <h3 class="box-title">Hover Data Table</h3>
                    </div-->
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="agent_container" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                            <th>id</th>
                            <th>姓名</th>
                            <th>邀请码</th>
                            <th>入驻时间</th>
                            <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($agents))
                                <tr>没有记录</tr>
                            @else
                                @foreach($agents as $agent)
                                    <tr>
                                        <td>{{ $agent['id'] }}</td>
                                        <td>{{ $agent['name'] }}</td>
                                        <td>{{ $agent['invite_code'] }}</td>
                                        <td>{{ date('Y-m-d', strtotime($agent['created_at'])) }}</td>
                                        <td>
                                            <button type="button" onclick="unBanAgent({{ $agent['id'] }})" class="btn btn-primary">解封</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $agents->links() }}
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
        function unBanAgent(id) {
            var data = {
                'id': id,
            }
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/general_agent/unban",
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
        $('#general_agent').addClass('active');
        $('#general_agent_banlist').addClass('active');
    </script>
@endsection