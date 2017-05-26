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
    <form class="form-horizontal" method="POST" action="{{  route('recharge.user') }}">
        {{  csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="user_name" class="col-sm-2 control-label">用户账号</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="user_name" placeholder="请输入用户账号" required>
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
                    <input type="radio" value="1" name="recharge_type" checked>&nbsp;&nbsp;&nbsp;&nbsp;房卡&nbsp;&nbsp;
                    <input type="radio" value="2" name="recharge_type">&nbsp;&nbsp;&nbsp;&nbsp;钻石&nbsp;&nbsp;
                    <input type="radio" value="3" name="recharge_type">&nbsp;&nbsp;&nbsp;&nbsp;欢乐豆
                </div>

            </div>
        </div>
        <button type="submit" class="btn btn-info pull-right">充值</button>
        @foreach ($accounts as $account)
            <div class="form-group">
                @if($account['type'] == 1)
                    <label class="col-sm-2 control-label">房卡</label>
                    <span class="info-box-number" style="color: red"><i>{{ $account['balance'] }}</i></span>
                @elseif($account['type'] == 2)
                    <label class="col-sm-2 control-label">钻石</label>
                    <span class="info-box-number" style="color: red"><i>{{ $account['balance'] }}</i></span>
                @elseif($account['type'] == 3)
                    <label class="col-sm-2 control-label">欢乐豆</label>
                    <span class="info-box-number" style="color: red"><i>{{ $account['balance'] }}</i></span>
                @else
                @endif
            </div>
        @endforeach

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
