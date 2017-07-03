@extends('admin_template')

@section('head')
<link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
    <style>
        .agent {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">新增用户</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" method="POST" action="{{  route('user.doadd') }}">
        {{  csrf_field() }}
        <div class="box-body">
            @include('widgets.admin')
        </div>
        <button type="submit" class="btn btn-info pull-right">提交</button>
    </form>

    <div id="agent_widget" style="display: none">
        @include('widgets.agent')
    </div>
@endsection

@section('script')
<script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        /*$(".select2").select2({
            placeholder: "Select a state",
            allowClear: true,
            minimumResultsForSearch: Infinity
        });*/
        $(".role_single").select2();
        $(".city_multi").select2({
            placeholder: "请选择开通城市",
            maximumSelectionLength: 3,
            tags: true
        });
    });
    $('.select2').change(function () {
        if (this.value == 'admin') {
            var agents = $('.agent');
            for (var i = 0; i < agents.length; i++) {
                $(agents[i]).css('display', 'none');
            }
        } else {
            addAgentElem();
        }
    })

    function addAgentElem()
    {
        $('.box-body').append(
            $('#agent_widget').html()
        );
    }

    function addFirstAgentElem() {
        
    }
</script>
@endsection