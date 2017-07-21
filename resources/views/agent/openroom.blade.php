@extends('admin_template')

@section('head')
    <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.css") }}">
@endsection

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
                            @role(['agent', 'first_agent', 'general_agent'])
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
    <script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.js") }}"></script>
    <script>
        $(document).ready(function() {
            $(".city_multi").select2({
                placeholder: "请选择开通城市",
                maximumSelectionLength: 3,
                tags: true
            });
            $('#openroom').addClass('active');
        });

    </script>
@endsection

