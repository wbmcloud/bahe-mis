@extends('admin_template')

@section('content')
<section class="content-header">
    <h1>
        操作提示
    </h1>
</section>

<section class="content">
    @if(session('message'))
    <h3 style="text-align: center"><i class="fa fa-warning text-yellow"></i> 操作失败</h3>
    @else
    <h3 style="text-align: center"><i class="fa fa-warning text-yellow"></i> 操作成功</h3>
    @endif
    <h5 style="text-align: center">
        @if(session('message'))
        {{ session('message') }}
        @endif
        <a href="/dashboard">返回到首页</a>
    </h5>
</section>
@endsection
