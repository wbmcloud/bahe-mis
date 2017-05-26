@extends('admin_template')

@section('head')

@endsection

@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">新增一级代理</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" method="POST" action="{{  route('general_agent.add') }}">
        {{  csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">姓名</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" placeholder="请输入姓名" required>
                </div>
            </div>
            <div class="form-group">
                <label for="invite_code" class="col-sm-2 control-label">邀请码</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="invite_code" placeholder="请输入邀请码" required>
                </div>
            </div>
            <div class="form-group">
                <label for="tel" class="col-sm-2 control-label">手机号</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="tel" placeholder="请输入手机号" required>
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
            <button type="submit" class="btn btn-info pull-right">提交</button>
        </div>
    </form>
@endsection

@section('script')
<script type="text/javascript">
$('#general_agent').addClass('active');
$('#general_agent_add').addClass('active');
</script>
@endsection