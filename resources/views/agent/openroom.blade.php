@extends('admin_template')

@section('content')
    <section class="content-header">
        <h1>
            代开房
        </h1>
    </section>
    <section class="content">
        <form class="form-horizontal" method="POST" action="{{  route('agent.doopenroom') }}">
            {{  csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label for="server_id" class="col-sm-2 control-label">选择代开房城市</label>

                    <div class="col-sm-10">
                        <select class="city_multi form-control select2" name="server_id" style="width: 100%;" required>
                            @role(['super', 'admin'])
                            @foreach($cities as $city)
                            <option value="{{ $city['server']['server_id'] }}">{{ $city['city_name'] }}</option>
                            @endforeach
                            @endrole
                            @role(['agent', 'first_agent'])
                            <option value="{{ $agent['city']['server']['server_id'] }}">{{ $agent['city']['city_name'] }}</option>
                            @endrole
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-info pull-right">开房</button>
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

