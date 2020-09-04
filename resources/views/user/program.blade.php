@extends('layouts.admin')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Данные структуры - {{ $user->name }}</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-block">
                            <form  method="POST" class="form-horizontal user_create">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-6">
                                        <label  class="m-t-10" for="position">Пакет:</label>
                                        <div class="input-group">
                                            <select class="custom-select form-control required" id="package_id" name="package_id" onchange="getStatus(this)">
                                                <option value="0">Только регистрация - ${{ env('REGISTRATION_FEE') }} / 0 PV</option>
                                                @foreach(\App\Models\Package::where('status',1)->get() as $item)
                                                    <option value="{{ $item->id }}" @if(old('package_id', $user->package_id) == $item->id) selected @endif>{{ $item->title }} - ${{ $item->cost }}+${{ env('REGISTRATION_FEE') }}(${{ $item->cost+env('REGISTRATION_FEE') }}) / {{ $item->pv  }} PV</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('package_id'))
                                                <span class="text-danger"><small>{{ $errors->first('package_id') }}</small></span>
                                            @endif
                                            <input type="hidden" value="1" id="#status0">
                                            @foreach(\App\Models\Package::where('status',1)->get() as $item)
                                                <input type="hidden" value="{{ $item->rank }}" id="#status{{$item->id}}">
                                            @endforeach
                                        </div>
                                        <label  class="m-t-10" for="position">Статус:</label>
                                        <div class="input-group">
                                            <select class="custom-select form-control required" id="status_id" name="status_id">
                                                @foreach(\App\Models\Status::all() as $item)
                                                    <option value="{{ $item->id }}" @if(old('status_id', $user_program->status_id) == $item->id) selected @endif>{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('status_id'))
                                                <span class="text-danger"><small>{{ $errors->first('status_id') }}</small></span>
                                            @endif
                                        </div>
                                        <label  class="m-t-10" for="position">Офис:</label>
                                        <div class="input-group">
                                            <select class="form-control form-control-line" name="office_id" id="user_offices">
                                                @foreach($offices as $item)
                                                    <option value="{{ $item->id }}" @if(old('office_id', $user->office_id) == $item->id) selected @endif>{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('office_id'))
                                                <span class="text-danger"><small>{{ $errors->first('office_id') }}</small></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success" type="submit">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @include('layouts.footer')
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection

@push('scripts')
    @if (session('status'))
        <script>
            $.toast({
                heading: 'Результат действии',
                text: '{{ session('status') }}',
                position: 'top-left',
                loaderBg:'#ffffff',
                icon: 'warning',
                hideAfter: 30000,
                stack: 6
            });
        </script>
    @endif

    <script>
        function getSponsorUsers(inviter_id) {
            $.ajax({
                type: "GET",
                url: "/sponsor_users",
                data: 'inviter_id='+inviter_id.value,
                success: function (data) {
                    console.log('Submission was successful.');
                    console.log(data);

                    $('#sponsor_users')
                        .find('option')
                        .remove()
                        .end()
                        .append(data)
                        .val('whatever');

                    $('#sponsor_positions')
                        .find('option')
                        .remove();

                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        }

        function getPosition(sponsor_id) {
            $.ajax({
                type: "GET",
                url: "/sponsor_positions",
                data: 'sponsor_id='+sponsor_id.value,
                success: function (data) {
                    console.log('Submission was successful.');
                    console.log(data);

                    $('#sponsor_positions')
                        .find('option')
                        .remove()
                        .end()
                        .append(data)
                        .val('whatever')
                    ;

                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        }

        function getOffices(city_id) {
            $.ajax({
                type: "GET",
                url: "/user_offices",
                data: 'city_id='+city_id.value,
                success: function (data) {
                    console.log('Submission was successful.');
                    console.log(data);

                    $('#user_offices')
                        .find('option')
                        .remove()
                        .end()
                        .append(data)
                        .val('whatever')
                    ;

                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        }

        function getStatus(status) {
            text = '#status'+status.value;
            status_id = document.getElementById(text).value;
            console.log(status_id);
            $("#status_id").val(status_id);
        }
    </script>
@endpush

