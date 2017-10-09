@extends('admin_template')

@section('head')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/iCheck/square/blue.css") }}">
@endsection

@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">用户充值</h3>
    </div>
    @include('widgets.error')
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" method="POST" action="{{  route('recharge.douser') }}">
        {{  csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="role_id" class="col-sm-2 control-label">角色id</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="role_id" placeholder="请输入角色id" value="{{ old('role_id') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label for="num" class="col-sm-2 control-label">充值数量</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="num" placeholder="请输入充值数" value="{{ old('num') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label for="recharge_type" class="col-sm-2 control-label">充值类型</label>

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
            @role(['agent', 'first_agent', 'general_agent'])
            @if(!empty($account) && (!empty($account['diamond_balance']) ||
                !empty($account['card_balance']) || !empty($account['bean_balance'])))
            <div class="form-group">
                <label class="col-sm-2 control-label">账户余额</label>
                <div class="col-sm-10">
                    @if($account['diamond_balance'])
                        <label class="control-label">钻石</label>
                        <span style="color: red;width: 5%;"><i>{{ $account['diamond_balance'] }}</i></span>
                        <span>&nbsp;&nbsp;</span>
                    @endif
                    @if($account['card_balance'])
                        <label class="control-label">房卡</label>
                        <span style="color: red"><i>{{ $account['card_balance'] }}</i></span>
                        <span>&nbsp;&nbsp;</span>
                    @endif
                    @if($account['bean_balance'])
                        <label class="control-label">欢乐豆</label>
                        <span style="color: red"><i>{{ $account['bean_balance'] }}</i></span>
                    @endif
                </div>
            </div>
            @endif
            @endrole
        </div>
        <button type="submit" class="btn btn-info pull-right">充值</button>
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
        $('#recharge').addClass('active');
        $('#user_recharge').addClass('active');
    </script>
@endsection
