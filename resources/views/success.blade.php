@extends('admin_template')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            操作提示
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> 操作成功</h3>

                <p>
                    <a href="/dashboard">return to dashboard</a>
                </p>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
@endsection
