@extends('admin_template')
@section('head')
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
    <style>
    .pagination {
        margin-left: 40%;
    }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            代理人列表
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
                    <div class="input-group margin" style="width:40%;">
                        <input id="query_str" type="text" class="col-sm-2 form-control" placeholder="请输入用户名">
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
                            <th>交易总数</th>
                            <th>成为代理时间</th>
                            <th>代理操作</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($agents->total()))
                                <tr><td colspan="5">没有记录</td></tr>
                            @else
                                @foreach($agents as $agent)
                                    <tr>
                                        <td><a href="{{ route('agent.info') . '?id=' . $agent['id'] }}">{{ $agent['id'] }}</a></td>
                                        <td>{{ $agent['name'] }}</td>
                                        <td>{{ $agent['account']['total'] }}</td>
                                        <!--td>{{ date('Y-m-d', strtotime($agent['created_at'])) }}</td-->
                                        <td>{{ $agent['created_at'] }}</td>
                                        <td>
                                            <button type="button" onclick="rechargeList('{{ route('agent.rechargelist') . '?id=' . $agent['id'] }}')" class="btn btn-primary">消费记录</button>
                                            <button type="button" onclick="banAgent({{ $agent['id'] }})" class="btn btn-primary">封禁</button>
                                            <button type="button" onclick="editAgent({{ $agent['id'] }})" class="btn btn-primary">修改信息</button>
                                            <button type="button" onclick="resetPassword({{ $agent['id'] }})" class="btn btn-primary">重置密码</button>
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
                <form class="edit_agent_form form-horizontal" method="POST" action="">
                    {{  csrf_field() }}
                    <div class="box-body">
                        <input type="hidden" class="form-control" name="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">用户名</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" placeholder="请输入用户名" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city_id" class="col-sm-2 control-label">开通城市</label>

                            <div class="col-sm-10">
                                <select class="city_multi form-control select2" name="city_id" style="width: 100%;" required>
                                    @foreach($cities as $city)
                                        <option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="invite_code" class="col-sm-2 control-label">邀请码</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="invite_code" placeholder="请输入邀请码">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uin" class="col-sm-2 control-label">QQ号</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="uin" placeholder="请输入QQ号">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="wechat" class="col-sm-2 control-label">微信号</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="wechat" placeholder="请输入微信号">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uin_group" class="col-sm-2 control-label">QQ群</label>

                            <div class="col-sm-10">
                                <textarea class="form-control" name="uin_group" placeholder="请输入QQ群，可输入多个，每行一个"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tel" class="col-sm-2 control-label">手机号</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="tel" placeholder="请输入手机号">
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

    <div class="reset_passwd modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">重置密码</h4>
                </div>
                <!-- form start -->
                <form class="reset_password_form form-horizontal" method="POST" action="">
                    {{  csrf_field() }}
                    <div class="box-body">
                        <input type="hidden" class="form-control" name="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">输入密码</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password" placeholder="请输入密码">
                            </div>
                        </div>
                        <button type="button" onclick="savePassword()" class="btn btn-info pull-right">提交</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection
@section('script')
    <script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
    <script>
        var city_select = $(".city_multi").select2({
            placeholder: "请选择开通城市",
        });

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
                url: "/api/agent/ban",
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

        function resetPassword(id) {
            $("input[name='id']").val(id);
            $('.reset_passwd').modal('show');
        }

        function savePassword() {
            var _id = $("input[name='id']").val();
            var _password = $("input[name='password']").val();
            var _data = {
                id: _id,
                password: _password
            };
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/agent/reset",
                data: _data,
                success: function (res) {
                    $('.reset_passwd').modal('hide');
                    $('#msg').html(res.msg);
                    $("#confirm").attr("data-dismiss", "modal");
                    $("#confirm").removeAttr("onclick");
                    $('.modal_container').modal('show');
                }
            });
        }

        function editAgent(id) {
            var data = {
                'id': id,
            }
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/agent/info",
                data: data,
                success: function (res) {
                    if (res.code) {
                        $('#msg').html(res.msg);
                        $("#confirm").attr("data-dismiss", "modal");
                        $("#confirm").removeAttr("onclick");
                        $('.modal_container').modal('show');
                    } else {
                        var data = res.data;
                        city_select.val(data.city_id).trigger('change');
                        $("input[name='id']").val(data.id);
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
            var form_data = $('.edit_agent_form').serializeArray();
            $.each(form_data, function() {
                data[this.name] = this.value;
            });
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                type: 'POST',
                url: "/api/agent/save",
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
                $('#msg').html("请输入用户名");
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

        $('#agent').addClass('active');
        $('#agent_list').addClass('active');
    </script>
@endsection