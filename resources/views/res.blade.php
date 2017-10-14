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
            <h3>{{ $prompt }}<span id="room_number" style="color: red; margin-left: 15px;">{{ $data }}</span></h3>
            <button type="button" class="btn btn-info">复制</button>
            <p><a href="{{ \App\Common\ParamsRules::IF_DASHBOARD }}">返回首页</a></p>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('script')
    <!-- 2. Include library -->
    <script src="{{ asset("/bower_components/clipboard/dist/clipboard.min.js") }}"></script>

    <!-- 3. Instantiate clipboard -->
    <script>
        var clipboard = new Clipboard('.btn', {
            text: function() {
                return $('#room_number').text();
            }
        });

        clipboard.on('success', function(e) {
            console.log(e);
            alert('复制成功！');
        });

        clipboard.on('error', function(e) {
            console.log(e);
        });
    </script>

@endsection

