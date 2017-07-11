@extends('admin_template')

@section('content')
    <div class="text-center" style="margin-top: 20%;">
        <h3>{{ $message }}</h3>

        <h5>浏览器页面将在<span class="login_time" style="color: red">{{ $jump_time }}</span>秒后跳转
            <a href="{{ $jump_url }}" style="color: cornflowerblue">手动点击跳转</a>......</h5>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function () {
            var url = "{{ $jump_url }}"
            var login_time = parseInt($('.login_time').text());
            var time = setInterval(function () {
                login_time = login_time - 1;
                $('.login_time').text(login_time);
                if (login_time == 0) {
                    clearInterval(time);
                    window.location.href = url;
                }
            }, 1000);
        })
    </script>
@endsection
