@extends('admin_template')

@section('content')
    <div class="text-center" style="margin-top: 20%;">
        <h3>尊敬的 <span style="color: red;">{{ Auth::user()->roles[0]['display_name'] }} {{ Auth::user()->name }}</span> 您好！！！</h3>

        <h4>请在左侧栏选择对应的操作。</h4>
    </div>
@endsection