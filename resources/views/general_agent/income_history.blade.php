@extends('admin_template')
@section('head')
    <style>
        .pagination {
            margin-left: 40%;
        }
    </style>
    <!-- daterange picker -->
    <link rel="stylesheet"
          href="{{ asset("/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.css") }}">

@endsection
@section('content')
    <section class="content-header">
        <h1>
            历史记录查询
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box-body">
                    <div class="box">
                        <table id="agent_container" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>日期</th>
                                <th>收入</th>
                                <th>是否到账</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($history_income_list->total()))
                                <tr>
                                    <td colspan="3">没有记录</td>
                                </tr>
                            @else
                                @foreach($history_income_list as $history_income)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::now()->subWeek(\Carbon\Carbon::now()->weekOfYear - $history_income['week'])->startOfWeek()->toDateString() }}
                                        - {{ \Carbon\Carbon::now()->subWeek(\Carbon\Carbon::now()->weekOfYear - $history_income['week'])->endOfWeek()->toDateString() }}</td>
                                        <td>{{ $history_income['amount'] }}</td>
                                        @if($history_income['status'] == 0)
                                            <td>否</td>
                                        @else
                                            <td>是</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $history_income_list->links() }}
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    </div>
@endsection
@section('script')
    <script>
        $('#data_stat').addClass('active');
        $('#history').addClass('active');
    </script>
@endsection