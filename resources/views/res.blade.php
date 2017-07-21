@extends('admin_template')
@section('head')
    <style>
        .open_room {
            font-size: 25px;
            font-family: sans-serif;
            color: red;
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            结果页面
        </h1>
    </section>
    <section class="content">
        <div class="text-center" style="margin-top: 20%;">
            <h3>{{ $prompt }}<span style="color: red; margin-left: 15px;">{{ $data }}</span></h3>
            <a href="{{ \App\Common\ParamsRules::IF_DASHBOARD }}">返回首页</a>
        </div>
    </section>
    <!-- /.content -->
@endsection

