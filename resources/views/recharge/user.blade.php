@extends('admin_template')

@section('head')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/iCheck/square/blue.css") }}">
@endsection

@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">用户充值</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" method="POST" action="{{  route('recharge.douser') }}">
        {{  csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="role_id" class="col-sm-2 control-label">角色id</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="role_id" placeholder="请输入角色id" required>
                </div>
            </div>
            <div class="form-group">
                <label for="num" class="col-sm-2 control-label">充值数量</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="num" placeholder="请输入充值数" required>
                </div>
            </div>
            <div class="form-group">
                <label for="recharge_type" class="col-sm-2 control-label">充值类型</label>

                <div class="col-sm-10">
                    <input type="radio" value="1" name="recharge_type">&nbsp;&nbsp;&nbsp;&nbsp;钻石&nbsp;&nbsp;
                    <input type="radio" value="2" name="recharge_type" checked>&nbsp;&nbsp;&nbsp;&nbsp;房卡&nbsp;&nbsp;
                    <input type="radio" value="3" name="recharge_type">&nbsp;&nbsp;&nbsp;&nbsp;欢乐豆
                </div>

            </div>
            @role('agent')
            <div class="form-group">
                <label class="col-sm-2 control-label">账户余额</label>
                <div class="col-sm-10">
                    @foreach ($accounts as $account)
                        @if($account['type'] == 1)
                            <label class="control-label">钻石</label>
                            <span style="color: red;width: 5%;"><i>{{ $account['balance'] }}</i></span>
                            <span>&nbsp;&nbsp;</span>
                        @elseif($account['type'] == 2)
                            <label class="control-label">房卡</label>
                            <span style="color: red"><i>{{ $account['balance'] }}</i></span>
                            <span>&nbsp;&nbsp;</span>
                        @elseif($account['type'] == 3)
                            <label class="control-label">欢乐豆</label>
                            <span style="color: red"><i>{{ $account['balance'] }}</i></span>
                        @else
                        @endif
                    @endforeach
                </div>
            </div>
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
