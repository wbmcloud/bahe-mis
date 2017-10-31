@extends('admin_template')
@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">新增管理员</h3>
    </div>
    @include('widgets.error')
    <form class="form-horizontal" method="POST" action="{{  route('user.doadd', ['type' => \App\Common\Constants::ADD_USER_TYPE_ADMIN]) }}">
        {{  csrf_field() }}
        <div class="box-body">
            @include('widgets.admin')
            <button type="submit" id="add_admin_btn" class="btn btn-info pull-right">提交</button>
        </div>
    </form>
@endsection
@section('script')
    <script>
        $('#add_admin_btn').click(function () {
            $('#add_admin_btn').attr('disabled', 'true');
            $('.form-horizontal').submit();
        });
    </script>
@endsection