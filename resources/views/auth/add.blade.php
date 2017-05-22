@extends('admin_template')

@section('head')
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
    <style>
        .agent {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">新增用户</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" method="POST" action="{{  route('user.add') }}">
        {{  csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">用户名</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" placeholder="请输入用户名" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">密码</label>

                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password" name="password" placeholder="请输入密码" required>
                </div>
            </div>
            <div class="form-group">
                <label for="role" class="col-sm-2 control-label">角色</label>
                <div class="col-sm-10">
                    <select class="js-example-basic-single" name="role_name" style="width: 100%" required>
                        @role('super')
                        <option value="admin">管理员</option>
                        @endrole
                        <option value="agent">代理</option>
                    </select>
                </div>
            </div>
            <div class="agent form-group">
                <label for="invite_code" class="col-sm-2 control-label">邀请码</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="invite_code" placeholder="请输入邀请码">
                </div>
            </div>
            <div class="agent form-group">
                <label for="uin" class="col-sm-2 control-label">QQ号</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="uin" placeholder="请输入QQ号">
                </div>
            </div>
            <div class="agent form-group">
                <label for="wechat" class="col-sm-2 control-label">微信号</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="wechat" placeholder="请输入微信号">
                </div>
            </div>
            <div class="agent form-group">
                <label for="uin_group" class="col-sm-2 control-label">QQ群</label>

                <div class="col-sm-10">
                    <textarea class="form-control" name="uin_group" placeholder="请输入QQ群，可输入多个，每行一个"></textarea>
                </div>
            </div>
            <div class="agent form-group">
                <label for="tel" class="col-sm-2 control-label">手机号</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="tel" placeholder="请输入手机号">
                </div>
            </div>
            <div class="agent form-group">
                <label for="bank_card" class="col-sm-2 control-label">银行卡号</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="bank_card" placeholder="请输入银行卡号">
                </div>
            </div>
            <div class="agent form-group">
                <label for="id_card" class="col-sm-2 control-label">身份证号</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="id_card" placeholder="请输入身份证号">
                </div>
            </div>
            <button type="submit" class="btn btn-info pull-right">提交</button>
        </div>
    </form>
@endsection

@section('script')
<script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".js-example-basic-single").select2({
            placeholder: "Select a state",
            allowClear: true,
            minimumResultsForSearch: Infinity
        });
    });
    $('.js-example-basic-single').change(function () {
        if (this.value == 'admin') {
            var agents = $('.agent');
            for (var i = 0; i < agents.length; i++) {
                $(agents[i]).css('display', 'none');
            }
        } else {
            var agents = $('.agent');
            for (var i = 0; i < agents.length; i++) {
                $(agents[i]).css('display', 'block');
            }
        }
    })
</script>
@endsection