@extends('admin_template')

@section('head')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/iCheck/square/blue.css") }}">
@endsection

@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">代理充值</h3>
    </div>
    @include('widgets.error')
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" method="POST" action="{{  route('recharge.doagent') }}">
        {{  csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="user_name" class="col-sm-2 control-label">代理账号</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="user_name" placeholder="请输入代理账号" value="{{ old('user_name') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label for="recharge_name" class="col-sm-2 control-label">充值数量</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="num" placeholder="请输入充值数" value="{{ old('num') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label for="recharge_name" class="col-sm-2 control-label">赠送数量</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="give_num" placeholder="请输入赠送数" value="{{ old('give_num') }}">
                </div>
            </div>
            <div class="form-group">
                <label for="recharge_name" class="col-sm-2 control-label">充值类型</label>

                <div class="col-sm-10">
                    @if(old('recharge_type'))
                        <!--input type="radio" value="1" name="recharge_type"
                               @if(old('recharge_type') == 1) checked @endif>&nbsp;&nbsp;&nbsp;&nbsp;钻石&nbsp;&nbsp;-->
                        <input type="radio" value="2" name="recharge_type"
                               @if(old('recharge_type') == 2) checked @endif>&nbsp;&nbsp;&nbsp;&nbsp;房卡&nbsp;
                        <!--input type="radio" value="3" name="recharge_type"
                               @if(old('recharge_type') == 3) checked @endif>&nbsp;&nbsp;&nbsp;&nbsp;欢乐豆-->
                    @else
                        <!--input type="radio" value="1" name="recharge_type">&nbsp;&nbsp;&nbsp;&nbsp;钻石&nbsp;&nbsp;-->
                        <input type="radio" value="2" name="recharge_type" checked>&nbsp;&nbsp;&nbsp;&nbsp;房卡&nbsp;
                        <!--input type="radio" value="3" name="recharge_type">&nbsp;&nbsp;&nbsp;&nbsp;欢乐豆-->
                    @endif
                </div>

            </div>
        </div>
        <button type="submit" id="agent_recharge_btn" class="btn btn-info pull-right">充值</button>
    </form>
@endsection
@section('script')
    <!-- iCheck -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });

        $('#agent_recharge_btn').click(function () {
            $('#agent_recharge_btn').attr('disabled', 'true');
            $('.form-horizontal').submit();
        });

        $('#recharge').addClass('active');
        $('#agent_recharge').addClass('active');
    </script>
@endsection
