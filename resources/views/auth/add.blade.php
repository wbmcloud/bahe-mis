@extends('admin_template')

@section('head')
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
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
        </div>
        <button type="submit" class="btn btn-info pull-right">提交</button>
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
</script>
@endsection