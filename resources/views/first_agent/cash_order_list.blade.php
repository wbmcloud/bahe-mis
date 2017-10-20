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
            上周打款列表
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
                            <th>id</th>
                            <th>姓名</th>
                            <th>上周打款金额（单位：元）</th>
                            <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($cash_orders->count()))
                                <tr><td colspan="4">没有记录</td></tr>
                            @else
                                @foreach($cash_orders as $cash_order)
                                    <tr>
                                        <td>{{ $cash_order['id'] }}</td>
                                        <td>{{ $cash_order['name'] }}</td>
                                        <td>{{ $cash_order['amount'] }}</td>
                                        @if($cash_order['status'] == \App\Common\Constants::COMMON_ENABLE)
                                            <td>
                                                <button type="button" class="btn btn-primary" disabled>已打款</button>
                                            </td>
                                        @else
                                            <td>
                                                <button type="button" onclick="cashOrder({{ $cash_order['id'] }})" class="btn btn-primary">确认打款</button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $cash_orders->links() }}
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
        function cashOrder(id) {
            var data = {
                'id': id,
            }
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/first_agent/do_cash_order",
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

        $('#first_agent').addClass('active');
        $('#first_agent_cash_order').addClass('active');
    </script>
@endsection