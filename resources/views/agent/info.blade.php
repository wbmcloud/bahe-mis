@extends('admin_template')

@section('content')
    <section class="content-header">
        <h1>
            代理人信息
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">id</label>
                            <span>{{ $agent_info['id'] }}</span>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">用户名</label>
                            <span>{{ $agent_info['name'] }}</span>
                        </div>
                        @if($agent_info['invite_code'])
                        <div class="form-group">
                            <label class="col-sm-2 control-label">邀请码</label>
                            <span>{{ $agent_info['invite_code'] }}</span>
                        </div>
                        @endif
                        @if($agent_info['uin'])
                        <div class="form-group">
                            <label class="col-sm-2 control-label">QQ号</label>
                            <span>{{ $agent_info['uin'] }}</span>
                        </div>
                        @endif
                        @if($agent_info['wechat'])
                        <div class="form-group">
                            <label class="col-sm-2 control-label">微信号</label>
                            <span>{{ $agent_info['wechat'] }}</span>
                        </div>
                        @endif
                        @if($agent_info['uin_group'])
                        <div class="form-group">
                            <label class="col-sm-2 control-label">QQ群</label>
                            <span>{{ $agent_info['uin_group'] }}</span>
                        </div>
                        @endif
                        @if($agent_info['uin_group'])
                        <div class="form-group">
                            <label class="col-sm-2 control-label">手机号</label>
                            <span>{{ $agent_info['tel'] }}</span>
                        </div>
                        @endif
                        @if($agent_info['bank_card'])
                        <div class="form-group">
                            <label class="col-sm-2 control-label">银行卡号</label>
                            <span>{{ $agent_info['bank_card'] }}</span>
                        </div>
                        @endif
                        @if($agent_info['id_card'])
                        <div class="form-group">
                            <label class="col-sm-2 control-label">身份证号</label>
                            <span>{{ $agent_info['id_card'] }}</span>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="col-sm-2 control-label">成为代理时间</label>
                            <span>{{ $agent_info['created_at'] }}</span>
                        </div>
                        @if(isset($agent_info['account']['total']))
                            <div class="form-group">
                                <label class="col-sm-2 control-label">交易总数</label>
                                <span style="color: red;">{{ $agent_info['account']['total'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection