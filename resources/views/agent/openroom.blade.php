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
    @include('widgets.error')
    <section class="content">
        <form class="form-horizontal" method="POST" action="{{  route('agent.doopenroom') }}">
            {{  csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label for="server_id" class="col-sm-2 control-label">城市选择</label>

                    <div class="col-sm-10">
                        @role(['super', 'admin'])
                        <select class="city_multi form-control select2" name="server" style="width: 100%;" onchange="changeFanxing(this.selectedOptions[0])" required>
                            @foreach($cities as $city)
                            <option value="{{ $city['server']['server_id'] . '-' . $city['city_id'] }}" data="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                            @endforeach
                        </select>
                        @endrole
                        @role(['agent', 'first_agent', 'general_agent'])
                        <select class="city_multi form-control select2" name="server" style="width: 100%;" onchange="changeFanxing(this.selectedOptions[0])" required>
                            @foreach(\App\Models\City::where(['p_city_id' => $agent['city']['p_city_id']])->get() as $city)
                                @if($city['city_id'] == $agent['city_id'])
                                    <option value="{{ $city['server']['server_id'] . '-' . $city['city_id'] }}" data="{{ $city['city_id'] }}" selected>{{ $city['city_name'] }}</option>
                                @else
                                    <option value="{{ $city['server']['server_id'] . '-' . $city['city_id'] }}" data="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        @endrole
                    </div>
                </div>
                <div class="form-group">
                    <label for="model" class="col-sm-2 control-label">游戏选择</label>

                    <div class="col-sm-10">
                        <input type="radio" value="1" name="game_type" checked>&nbsp;&nbsp;&nbsp;麻将&nbsp;&nbsp;
                        <input type="radio" value="2" name="game_type">&nbsp;&nbsp;&nbsp;斗地主
                    </div>
                </div>
                <div id="open_room_group">
                </div>
                <div class="form-group" id="open_room_group_pre">
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

                <button type="button" id="btn_open_room" class="btn btn-info pull-right">开房</button>
            </div>
        </form>
    </section>
    <!-- /.content -->
@endsection

@section('script')
    <script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
    <script>
        function changeFanxing(e) {
            var _data = $(e).attr('data');
            getFanxing(_data);
        }
        
        function getFanxing(city_id) {
            $.ajax({
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                url: "/api/basic/cityconfig",
                data: {
                    city_id: city_id,
                    game_type: $('input[name="game_type"]:checked').val()
                },
                success: function (res) {
                    var _html = '';
                    var _e = res.data.settings;
                    for (var i in _e)  {
                        if (i == 'extend_type') {
                            _html += '<div class="form-group">' +
                                '<label for="extend_type" class="col-sm-2 control-label">额外番型</label>' +
                                '<div class="col-sm-10">'
                            for (var j in _e[i]) {
                                _html += '<input type="checkbox" value="' + _e[i][j].id +
                                    '" name="extend_type[]" ' + (('undefined' == typeof(_e[i][j].is_checked)) ? 'checked' : '') +
                                    '>&nbsp;&nbsp;&nbsp;' + _e[i][j].desc + '&nbsp;&nbsp;'
                            }
                            _html += '</div></div>';
                        } else if (i == 'open_rands') {
                            _html += '<div class="form-group">' +
                                '<label for="open_rands" class="col-sm-2 control-label">开设局数</label>' +
                                '<div class="col-sm-10">';
                            for (var j in _e[i]) {
                                _html += '<input type="radio" value="' + _e[i][j].id +
                                    '" name="open_rands" ' + (('undefined' == typeof(_e[i][j].is_checked)) ? 'checked' : '') +
                                    '>&nbsp;&nbsp;&nbsp;' + _e[i][j].desc + '&nbsp;&nbsp;';
                            }
                            _html += '</div></div>';
                        } else if (i == 'top_mutiple') {
                            _html += '<div class="form-group">' +
                                '<label for="top_mutiple" class="col-sm-2 control-label">封顶倍数</label>' +
                                '<div class="col-sm-10">';
                            for (var j in _e[i]) {
                                _html += '<input type="radio" value="' + _e[i][j].id +
                                    '" name="top_mutiple" ' + (('undefined' == typeof(_e[i][j].is_checked)) ? 'checked' : '') +
                                    '>&nbsp;&nbsp;&nbsp;' + _e[i][j].desc + '&nbsp;&nbsp;';
                            }
                            _html += '</div></div>';
                        } else if (i == 'zhuang_type') {
                            _html += '<div class="form-group">' +
                                '<label for="zhuang_type" class="col-sm-2 control-label">玩法</label>' +
                                '<div class="col-sm-10">';
                            for (var j in _e[i]) {
                                _html += '<input type="radio" value="' + _e[i][j].id +
                                    '" name="zhuang_type" ' + (('undefined' == typeof(_e[i][j].is_checked)) ? 'checked' : '') +
                                    '>&nbsp;&nbsp;&nbsp;' + _e[i][j].desc + '&nbsp;&nbsp;';
                            }
                            _html += '</div></div>';
                        }
                    }
                    $('#open_room_group').html(_html);
                    $("input").iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '20%' // optional
                    });

                    $('input[name="game_type"]').on('ifChecked', function () {
                        var _data = $('select[name="server"] option:selected').attr('data');
                        getFanxing(_data);
                    });
                }
            });
        }

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

            $('#btn_open_room').click(function () {
                $('.btn').attr('disabled', 'true');
                $('.form-horizontal').submit();
            });
            $('#openroom').addClass('active');

            getFanxing({{$agent['city_id']}});
        });

    </script>
@endsection

