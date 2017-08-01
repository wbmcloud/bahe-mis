@extends('admin_template')

@section('head')
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
@endsection

@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">新增总代理</h3>
    </div>
    @include('widgets.error')
    <form class="form-horizontal" method="POST" action="{{  route('user.doadd', ['type' => \App\Common\Constants::ADD_USER_TYPE_FIRST_AGENT]) }}">
        {{  csrf_field() }}
        <div class="box-body">
            @include('widgets.first_agent')
            <button type="submit" class="btn btn-info pull-right">提交</button>
        </div>
    </form>
@endsection

@section('script')
<script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".role_single").select2();
        $(".city_multi").select2({
            placeholder: "请选择开通城市",
            maximumSelectionLength: 3,
            tags: true
        });
    });

    $('#first_agent').addClass('active');
    $('#first_agent_add').addClass('active');
</script>
@endsection