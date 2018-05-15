@extends('admin_template')

@section('head')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/iCheck/square/blue.css") }}">
@endsection

@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">绑定角色</h3>
    </div>
    @include('widgets.error')
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" method="POST" action="{{  route('game.dobindplayer') }}">
        {{  csrf_field() }}
        <div class="box-body">

            <div class="form-group">
                <label for="server" class="col-sm-2 control-label">请选择城市</label>
                <div class="col-sm-10">
                    @role(['super', 'admin'])
                    <select class="city_multi form-control select2" name="city" style="width: 100%;" required>
                        @foreach($cities as $city)
                            <option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                        @endforeach
                    </select>
                    @endrole
                    @role(['agent', 'first_agent', 'general_agent'])
                    <select class="city_multi form-control select2" name="city" style="width: 100%;" required>
                        @foreach(\App\Models\City::where(['p_city_id' => $agent['city']['p_city_id']])->get() as $city)
                            @if($city['city_id'] == $agent['city_id'])
                                <option value="{{ $city['city_id'] }}" selected>{{ $city['city_name'] }}</option>
                            @else
                                <option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                    @endrole
                </div>
            </div>

            <div class="form-group">
                <label for="model" class="col-sm-2 control-label">游戏选择</label>

                <div class="col-sm-10">
                    @role(['super', 'admin'])
                    <input type="radio" value="1" name="game_type" checked>&nbsp;&nbsp;&nbsp;麻将&nbsp;&nbsp;
                    <input type="radio" value="2" name="game_type">&nbsp;&nbsp;&nbsp;斗地主
                    @endrole
                    @role(['agent', 'first_agent', 'general_agent'])
                    @if(!in_array(\Illuminate\Support\Facades\Auth::user()->city_id, \App\Common\Constants::$bind_player_city_ban))
                    <input type="radio" value="1" name="game_type" checked>&nbsp;&nbsp;&nbsp;麻将&nbsp;&nbsp;
                    <input type="radio" value="2" name="game_type">&nbsp;&nbsp;&nbsp;斗地主
                    @else
                    <input type="radio" value="2" name="game_type" checked>&nbsp;&nbsp;&nbsp;斗地主
                    @endif
                    @endrole
                </div>
            </div>

            <div class="form-group">
                <label for="player_id" class="col-sm-2 control-label">角色id</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="player_id" placeholder="请输入角色id" value="{{ old('player_id') }}" required>
                </div>
            </div>
        </div>
        <button type="submit" id="bind_player_btn" class="btn btn-info pull-right">绑定</button>
    </form>
@endsection
@section('script')
    <!-- iCheck -->
    <script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
    <script>
        $(function () {
            $(".city_multi").select2({
                placeholder: "请选择开通城市",
                maximumSelectionLength: 3,
                tags: true
            });

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });

        $('#bind_player_btn').click(function () {
            $('#bind_player_btn').attr('disabled', 'true');
            $('.form-horizontal').submit();
        });

        $('#bindplayer').addClass('active');
    </script>
@endsection
