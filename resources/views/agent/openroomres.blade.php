@extends('admin_template')

@section('content')
    <section class="content-header">
        <h1>
            代开房结果
        </h1>
    </section>
    <section class="content">
        <form class="form-horizontal" method="POST" action="{{  route('agent.openroom') }}">
            {{  csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label for="server_id" class="col-sm-2 control-label">房间号</label>

                    <div class="col-sm-10">
                        <p>{{ $room_id }}</p>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#openroom').addClass('active');
        });

    </script>
@endsection

