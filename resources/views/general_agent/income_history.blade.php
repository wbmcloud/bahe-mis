@extends('admin_template')
@section('head')
    <style>
        .pagination {
            margin-left: 40%;
        }
    </style>
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
                                <th>收入（单位：元）</th>
                            </tr>
                            </thead>
                            <tbody id="agent_list_container">
                            @if(empty($history_income_list->count()))
                                <tr>
                                    <td colspan="3">没有记录</td>
                                </tr>
                            @else
                                @foreach($history_income_list as $history_income)
                                    <tr>
                                        <td>{{ \App\Common\Utils::getWeekIntervalDay($history_income['year'], $history_income['week'])['start_week'] . '-' .
                                         \App\Common\Utils::getWeekIntervalDay($history_income['year'], $history_income['week'])['end_week']}}</td>
                                        <td>{{ $history_income['amount'] }}</td>
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