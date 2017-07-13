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
            一级代理列表
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!--div class="box-header">
                        <h3 class="box-title">Hover Data Table</h3>
                    </div-->
                    <!-- /input-group -->
                    <div class="input-group margin" style="width:25%;">
                        <input id="query_str" type="text" class="col-sm-2 form-control" placeholder="请输入姓名或者邀请码">
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
                            <th>用户名</th>
                            <th>姓名</th>
                            <th>邀请码</th>
                            <th>发展代理数</th>
                            <th>入驻时间</th>
                            <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($agents->total()))
                                <tr><td colspan="5">没有记录</td></tr>
                            @else
                                @foreach($agents as $agent)
                                    <tr>
                                        <td>{{ $agent['id'] }}</td>
                                        <td>{{ $agent['user_name'] }}</td>
                                        <td>{{ $agent['name'] }}</td>
                                        <td>{{ $agent['invite_code'] }}</td>
                                        @if(isset($agents_count[$agent['invite_code']]))
                                        <td>{{ $agents_count[$agent['invite_code']]['count'] }}</td>
                                        @else
                                        <td>0</td>
                                        @endif
                                        <td>{{ date('Y-m-d', strtotime($agent['created_at'])) }}</td>
                                        <td>{{ $agent['created_at'] }}</td>
                                        <td>
                                            <button type="button" onclick="rechargeList('{{ route('general_agent.rechargelist', ['invite_code' => $agent['invite_code']]) }}')" class="btn btn-primary">充值信息</button>
                                            <button type="button" onclick="banAgent({{ $agent['id'] }})" class="btn btn-primary">封禁</button>
                                            <button type="button" onclick="editAgent({{ $agent['id'] }})" class="btn btn-primary">修改信息</button>
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

    <div class="edit_agent modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">修改信息</h4>
                </div>
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="">
                    {{  csrf_field() }}
                    <div class="box-body">
                        <input type="hidden" class="form-control" name="id">
                        <div class="form-group">
                            <label for="user_name" class="col-sm-2 control-label">用户名</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="user_name" placeholder="请输入用户名" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city_id" class="col-sm-2 control-label">开通城市</label>

                            <div class="col-sm-10">
                                <select class="city_multi form-control select2" name="city_id" style="width: 100%;" required disabled>
                                    @foreach($cities as $city)
                                        <option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">姓名</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" placeholder="请输入姓名" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="invite_code" class="col-sm-2 control-label">邀请码</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="invite_code" placeholder="请输入邀请码" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tel" class="col-sm-2 control-label">手机号</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="tel" placeholder="请输入手机号" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bank_card" class="col-sm-2 control-label">银行卡号</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="bank_card" placeholder="请输入银行卡号">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_card" class="col-sm-2 control-label">身份证号</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="id_card" placeholder="请输入身份证号">
                            </div>
                        </div>
                        <button type="button" onclick="saveAgent()" class="btn btn-info pull-right">提交</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('script')
    <script src="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
    <script>

        function rechargeList(url) {
            location.href = url;
        }

        function banAgent(id) {
            var data = {
                'id': id,
            }
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/general_agent/ban",
                data: data,
                success: function (res) {
                    $('#msg').html(res.msg);
                    $("#confirm").removeAttr("data-dismiss");
                    $("#confirm").attr("onclick", "hide()");
                    $('.modal_container').modal({
                        "show": true,
                        "backdrop": false,
                        "keyboard": false
                    });
                }
            });

        };
        
        function editAgent(id) {
            var data = {
                'id': id,
            }
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/general_agent/info",
                data: data,
                success: function (res) {
                    if (res.code) {
                        $('#msg').html(res.msg);
                        $("#confirm").attr("data-dismiss", "modal");
                        $("#confirm").removeAttr("onclick");
                        $('.modal_container').modal('show');
                    } else {
                        var data = res.data;
                        $("input[name='id']").val(data.id);
                        $("input[name='user_name']").val(data.user_name);
                        $("input[name='name']").val(data.name);
                        $("input[name='invite_code']").val(data.invite_code);
                        $("input[name='uin']").val(data.uin);
                        $("input[name='wechat']").val(data.wechat);
                        $("textarea[name='uin_group']").val(data.uin_group);
                        $("input[name='tel']").val(data.tel);
                        $("input[name='bank_card']").val(data.bank_card);
                        $("input[name='id_card']").val(data.id_card);
                        $('.edit_agent').modal('show');
                    }
                }
            });
        }
        
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

        function getCurrenturl()
        {
            return location.origin + location.pathname;
        }

        function query()
        {
            var query_str = $("#query_str").val();
            if (!query_str) {
                $('#msg').html("请输入姓名或者邀请码");
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

        $('#general_agent').addClass('active');
        $('#general_agent_list').addClass('active');
    </script>
@endsection