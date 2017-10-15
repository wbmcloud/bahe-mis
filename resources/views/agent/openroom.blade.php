@extends('admin_template')

@section('head')
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/iCheck/square/blue.css") }}">
@endsection

@section('content')
    <section class="content-header">
        <h1>
            代开房
        </h1>
    </section>
    <section class="content">
        <form class="form-horizontal" method="POST" action="{{  route('agent.doopenroom') }}">
            {{  csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label for="server_id" class="col-sm-2 control-label">城市选择</label>

                    <div class="col-sm-10">
                        <select class="city_multi form-control select2" name="server_id" style="width: 100%;" required>
                            @role(['super', 'admin'])
                            @foreach($cities as $city)
                            <option value="{{ $city['server']['server_id'] }}">{{ $city['city_name'] }}</option>
                            @endforeach
                            @endrole
                            @role(['agent', 'first_agent', 'general_agent'])
                            <option value="{{ $agent['city']['server']['server_id'] }}">{{ $agent['city']['city_name'] }}</option>
                            @endrole
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="model" class="col-sm-2 control-label">模式选择</label>

                    <div class="col-sm-10">
                        <input type="radio" value="1" name="model" checked>&nbsp;&nbsp;&nbsp;经典模式&nbsp;&nbsp;
                        <input type="radio" value="2" name="model">&nbsp;&nbsp;&nbsp;高番模式
                    </div>
                </div>
                <div class="form-group">
                    <label for="extend_type" class="col-sm-2 control-label">额外番型</label>

                    <div class="col-sm-10">
                        <input type="checkbox" value="4" name="extend_type[]" checked>&nbsp;&nbsp;&nbsp;宝牌&nbsp;&nbsp;
                        <input type="checkbox" value="1" name="extend_type[]" checked>&nbsp;&nbsp;&nbsp;站立胡&nbsp;&nbsp;
                        <input type="checkbox" value="2" name="extend_type[]" checked>&nbsp;&nbsp;&nbsp;带夹胡（夹、边）&nbsp;&nbsp;
                        <input type="checkbox" value="5" name="extend_type[]" checked>&nbsp;&nbsp;&nbsp;可断门&nbsp;&nbsp;
                        <input type="checkbox" value="6" name="extend_type[]" checked>&nbsp;&nbsp;&nbsp;清一色&nbsp;&nbsp;
                        <input type="checkbox" value="3" name="extend_type[]" checked>&nbsp;&nbsp;&nbsp;旋风杠&nbsp;&nbsp;
                        <input type="checkbox" value="7" name="extend_type[]" checked>&nbsp;&nbsp;&nbsp;包三家
                    </div>
                </div>
                <div class="form-group">
                    <label for="open_rands" class="col-sm-2 control-label">开设局数</label>

                    <div class="col-sm-10">
                        <input type="radio" value="8" name="open_rands" checked>&nbsp;&nbsp;&nbsp;8局（消耗房卡*1）&nbsp;&nbsp;
                        <input type="radio" value="16" name="open_rands">&nbsp;&nbsp;&nbsp;16局（消耗房卡*2）
                    </div>
                </div>
                <div class="form-group">
                    <label for="top_mutiple" class="col-sm-2 control-label">封顶倍数</label>

                    <div class="col-sm-10">
                        <input type="radio" value="32" name="top_mutiple" checked>&nbsp;&nbsp;&nbsp;32倍&nbsp;&nbsp;
                        <input type="radio" value="0" name="top_mutiple">&nbsp;&nbsp;&nbsp;不封顶
                    </div>
                </div>
                <div class="form-group">
                    <label for="voice_open" class="col-sm-2 control-label">语音</label>

                    <div class="col-sm-10">
                        <input type="checkbox" value="1" name="voice_open">&nbsp;&nbsp;
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
                <p style="color: red; text-align: center">代开房的房间保留15分钟，如果未开局不返还房卡</p>

                <button type="button" class="btn btn-info pull-right">开房</button>
            </div>
        </form>
    </section>
    <!-- /.content -->
@endsection

@section('script')
    <script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
    <script>
        $(document).ready(function() {
            $(".city_multi").select2({
                placeholder: "请选择开通城市",
                maximumSelectionLength: 3,
                tags: true
            });
            $("input").iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            $('.btn').click(function () {
                $('.btn').attr('disabled', 'true');
                $('.form-horizontal').submit();
            });
            $('#openroom').addClass('active');
        });

    </script>
@endsection

