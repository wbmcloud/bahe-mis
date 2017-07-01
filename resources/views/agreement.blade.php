<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>巴禾游戏 | 代理协议</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/skins/_all-skins.min.css") }}">

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="text-center">
                <h3>代理用户协议</h3>

                <form action="{{ route('user.agree') }}" method="POST">
                    {{ csrf_field() }}
                    <!-- textarea -->
                    <div class="form-group" style="margin-top: 20px;">
                        <textarea class="form-control" rows="15">
                        禁止使用平台进行赌博活动，一经发现你可以多买点禁止使用平台进行赌博活动，一经发现你可以多买点禁止使用平台进行赌博活动，一经发现你可以多买点禁止使用平台进行赌博活动，一经发现你可以多买点禁止使用平台进行赌博活动，一经发现你可以多买点禁止使用平台进行赌博活动，一经发现你可以多买点
                        用平台进行赌博活动，一经发现你可以多买点禁止使用平台进行赌博活动，一经发现你可以多买点禁止使用平台进行赌博活动，一经发现你可以多买点禁止使用平台进行赌博活动，一经发现你可以多买点
                        </textarea>
                        <input type="checkbox" name="is_accept" checked required> 我已阅读并同意上述协议
                    </div>
                    <button type="submit" class="btn btn-block btn-success center-block" style="width: 25%;margin-top: 20px;">进入</button>
                </form>
            </div>
        </section>
    </div>
@include('layouts.footer')

</div>
<script>

</script>
</body>
</html>
