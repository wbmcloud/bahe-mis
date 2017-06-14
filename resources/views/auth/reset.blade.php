@extends('admin_template')

@section('content')
<div class="box-header with-border">
    <h3 class="box-title">重置密码</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<form class="form-horizontal" method="POST" action="{{  route('user.doreset') }}">
    {{  csrf_field() }}
    <div class="box-body">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">旧密码</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="old_password" placeholder="请输入旧密码" required>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">新密码</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="new_password" placeholder="请输入新密码" required>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">确认新密码</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" name="dup_password" placeholder="请重复输入新密码" required>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-info pull-right">重置</button>
</form>
@endsection
