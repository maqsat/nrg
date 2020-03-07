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
                    <h3 class="text-themecolor m-b-0 m-t-0">Пользователи</h3>
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
                            <form action="{{url('user')}}" method="POST" class="form-horizontal user_create">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="m-t-10">{{ __('app.name') }}</label>
                                        <div class="input-group">
                                            <input type="text" value="{{ old('name') }}" name="name" class="form-control form-control-line">
                                            @if ($errors->has('name'))
                                                <br>
                                                <span class="text-danger"><small>{{ $errors->first('name') }}</small></span>
                                            @endif

                                        </div>
                                        <label class="m-t-10">{{ __('app.email') }}</label>
                                        <div class="input-group">
                                            <input type="email" value="" name="email" class="form-control form-control-line">
                                            @if ($errors->has('email'))
                                                <span class="text-danger"><small>{{ $errors->first('email') }}</small></span>
                                            @endif
                                        </div>
                                        <label class="m-t-10">Выберите пол:</label>
                                        <div class="input-group">
                                            <select class="custom-select form-control required" id="gender" name="gender">
                                                <option>Не указан</option>
                                                <option value="1"  @if(old('gender') == 1) selected @endif>Мужской</option>
                                                <option value="2"  @if(old('gender') == 2) selected @endif>Женский</option>
                                            </select>
                                        </div>
                                        <label class="m-t-10">{{ __('app.country') }}</label>
                                        <div class="input-group">
                                            <select class="form-control form-control-line" name="country_id">
                                                @foreach(\App\Models\Country::all() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="m-t-10">{{ __('app.address') }}</label>
                                        <div class="input-group">
                                            <input type="text" value="{{ old('address') }}" name="address" class="form-control form-control-line" placeholder="Район, мкр, улица, дом, квартира">
                                            @if ($errors->has('address'))
                                                <span class="text-danger"><small>{{ $errors->first('address') }}</small></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="m-t-10">{{ __('app.number') }}</label>
                                        <div class="input-group">
                                            <input type="text" value="{{ old('number') }}" name="number" class="form-control form-control-line">
                                            @if ($errors->has('number'))
                                                <span class="text-danger"><small>{{ $errors->first('number') }}</small></span>
                                            @endif
                                        </div>
                                        <label class="m-t-10">{{ __('app.birthday') }}</label>
                                        <div class="input-group">
                                            <input type="text" value="{{ old('birthday') }}" name="birthday" class="form-control form-control-line">
                                            @if ($errors->has('birthday'))
                                                <span class="text-danger"><small>{{ $errors->first('birthday') }}</small></span>
                                            @endif
                                        </div>
                                        <label class="m-t-10">{{ __('app.password') }}</label>
                                        <div class="input-group">
                                            <input type="text" value="{{ old('password', 'pass123456') }}" name="password" class="form-control form-control-line">
                                            @if ($errors->has('password'))
                                                <span class="text-danger"><small>{{ $errors->first('password') }}</small></span>
                                            @endif
                                        </div>
                                        <label  class="m-t-10" for="position">Город:</label>
                                        <div class="input-group">
                                            <select class="custom-select form-control required" id="city_id" name="city_id" onchange="getOffices(this)">
                                                <option>Выберите город</option>
                                                @foreach(\App\Models\City::where('status',1)->get() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="m-t-10">Дата регистрации</label>
                                        <div class="input-group">
                                            <input type="date" value="{{ old('created_at') }}" name="created_at" class="form-control">
                                            @if ($errors->has('created_at'))
                                                <span class="text-danger"><small>{{ $errors->first('created_at') }}</small></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="m-t-10">Менеджер</label>
                                        <div class="input-group">
                                            <select class="form-control form-control-line" name="inviter_id"  onchange="getSponsorUsers(this)">
                                                <option>Выберите менеджера</option>
                                                @foreach($users as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="m-t-10">Закреплен за(показывается только свободные позиции)</label>
                                        <div class="input-group">
                                            <select class="form-control form-control-line" name="sponsor_id" id="sponsor_users"  onchange="getPosition(this)"></select>
                                        </div>
                                        <label class="m-t-10">Позиция размещение</label>
                                        <div class="input-group">
                                             <select class="form-control form-control-line" name="position" id="sponsor_positions"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label  class="m-t-10" for="position">Пакет:</label>
                                        <div class="input-group">
                                            <select class="custom-select form-control required" id="package_id" name="package_id">
                                                <option value="0">Только регистрация - ${{ env('REGISTRATION_FEE') }} / 0 PV</option>
                                                @foreach(\App\Models\Package::where('status',1)->get() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->title }} - ${{ $item->cost+env('REGISTRATION_FEE') }} / {{ $item->pv  }} PV</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label  class="m-t-10" for="position">Статус:</label>
                                        <div class="input-group">
                                            <select class="custom-select form-control required" id="status_id" name="status_id">
                                                <option>Выберите статус</option>
                                                @foreach(\App\Models\Status::all() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label  class="m-t-10" for="position">Офис:</label>
                                        <div class="input-group">
                                            <select class="form-control form-control-line" name="office_id" id="user_offices"></select>
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

    </script>
@endpush
