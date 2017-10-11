<div class="form-group">
    <label for="user_name" class="col-sm-2 control-label">用户名</label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="user_name" placeholder="请输入用户名" value="{{ old('user_name') }}" required>
    </div>
</div>
<div class="form-group">
    <label for="password" class="col-sm-2 control-label">密码</label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="password" placeholder="请输入密码" required>
    </div>
</div>