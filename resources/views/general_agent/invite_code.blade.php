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
            邀请码列表
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
                            <th>邀请码</th>
                            <th>是否使用</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($codes->total()))
                                <tr><td colspan="5">没有记录</td></tr>
                            @else
                                @foreach($codes as $code)
                                    <tr>
                                        <td>{{ $code['id'] }}</td>
                                        <td>{{ $code['invite_code'] }}</td>
                                        @if($code['is_used'])
                                        <td>是</td>
                                        @else
                                        <td>否</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $codes->links() }}
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

        function saveAgent() {
            var data = {};
            var form_data = $('.form-horizontal').serializeArray();
            $.each(form_data, function() {
                data[this.name] = this.value;
            });
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                type: 'POST',
                url: "/api/general_agent/save",
                data: data,
                success: function (res) {
                    $('.edit_agent').modal('hide');
                    $('#msg').html(res.msg);
                    $("#confirm").attr("data-dismiss", "modal");
                    $("#confirm").removeAttr("onclick");
                    $('.modal_container').modal('show');
                }
            });
        }

        function hide() {
            $(".modal_container").modal('hide');
            location.reload();
        }
    </script>
@endsection