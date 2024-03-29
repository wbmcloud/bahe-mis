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
    <label for="name" class="col-sm-2 control-label">姓名</label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" placeholder="请输入姓名" value="{{ old('name') }}">
    </div>
</div>
<div class="form-group">
    <label for="invite_code" class="col-sm-2 control-label">邀请码</label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="invite_code" placeholder="请输入邀请码" value="{{ old('invite_code') }}">
    </div>
</div>
<div class="form-group">
    <label for="uin" class="col-sm-2 control-label">QQ号</label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="uin" placeholder="请输入QQ号" value="{{ old('uin') }}">
    </div>
</div>
<div class="form-group">
    <label for="wechat" class="col-sm-2 control-label">微信号</label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="wechat" placeholder="请输入微信号" value="{{ old('wechat') }}">
    </div>
</div>
<div class="form-group">
    <label for="uin_group" class="col-sm-2 control-label">QQ群</label>

    <div class="col-sm-10">
        <textarea class="form-control" name="uin_group" placeholder="请输入QQ群，可输入多个，每行一个">{{ old('uin_group') }}</textarea>
    </div>
</div>
<div class="form-group">
    <label for="tel" class="col-sm-2 control-label">手机号</label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="tel" placeholder="请输入手机号" value="{{ old('tel') }}">
    </div>
</div>
<div class="form-group">
    <label for="bank_card" class="col-sm-2 control-label">银行卡号</label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="bank_card" placeholder="请输入银行卡号" value="{{ old('bank_card') }}">
    </div>
</div>
<div class="form-group">
    <label for="id_card" class="col-sm-2 control-label">身份证号</label>

    <div class="col-sm-10">
        <input type="text" class="form-control" name="id_card" placeholder="请输入身份证号" value="{{ old('id_card') }}">
    </div>
</div>