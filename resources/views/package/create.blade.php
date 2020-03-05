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
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.name') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="" name="name" class="form-control form-control-line">
                                        @if ($errors->has('name'))
                                            <span class="help-block"><small>{{ $errors->first('name') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.number') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="" name="number" class="form-control form-control-line">
                                        @if ($errors->has('number'))
                                            <span class="help-block"><small>{{ $errors->first('number') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.email') }}</label>
                                    <div class="col-md-12">
                                        <input type="email" value="" name="email" class="form-control form-control-line">
                                        @if ($errors->has('email'))
                                            <span class="help-block"><small>{{ $errors->first('email') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12">Выберите пол:</label>
                                    <div class="col-sm-12">
                                        <select class="custom-select form-control required" id="gender" name="gender">
                                            <option>Не указан</option>
                                            <option value="1"  @if(old('gender') == 1) selected @endif>Мужской</option>
                                            <option value="2"  @if(old('gender') == 2) selected @endif>Женский</option>
                                        </select>
                                    </div>
                                    <div class="error-message"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.birthday') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="" name="birthday" class="form-control form-control-line">
                                        @if ($errors->has('birthday'))
                                            <span class="help-block"><small>{{ $errors->first('birthday') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12">{{ __('app.country') }}</label>
                                    <div class="col-sm-12">
                                        <select class="form-control form-control-line" name="country_id">
                                            @foreach(\App\Models\Country::all() as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.address') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="" name="address" class="form-control form-control-line">
                                        @if ($errors->has('address'))
                                            <span class="help-block"><small>{{ $errors->first('address') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.password') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="" name="password" class="form-control form-control-line">
                                        @if ($errors->has('password'))
                                            <span class="help-block"><small>{{ $errors->first('password') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Дата регистрации</label>
                                    <div class="col-md-12">
                                        <input type="date" value="" name="created_at" class="form-control form-control-line">
                                        @if ($errors->has('created_at'))
                                            <span class="help-block"><small>{{ $errors->first('created_at') }}</small></span>
                                        @endif
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group">
                                    <label class="col-md-12">Менеджер</label>
                                    <div class="col-md-12">
                                        <select class="form-control form-control-line" name="inviter_id"  onchange="getSponsorUsers(this)">
                                            <option>Выберите менеджера</option>
                                            @foreach(\App\User::all() as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Закреплен за</label>
                                    <div class="col-md-12">
                                        <select class="form-control form-control-line" name="sponsor_id" id="sponsor_users"  onchange="getPosition(this)">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Позиция размещение</label>
                                    <div class="col-md-12">
                                        <select class="form-control form-control-line" name="position" id="sponsor_positions">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-md-12" for="position">Офис:</label>
                                    <div class="col-md-12">
                                        <select class="custom-select form-control required" id="city_id" name="city_id">
                                            @foreach(\App\Models\City::where('status',1)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="error-message"></div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-md-12" for="position">Статус:</label>
                                    <div class="col-md-12">
                                        <select class="custom-select form-control required" id="status_id" name="status_id">
                                            <option>Выберите статус</option>
                                            @foreach(\App\Models\Status::all() as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="error-message"></div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-md-12" for="position">Пакет:</label>
                                    <div class="col-md-12">
                                        <select class="custom-select form-control required" id="package_id" name="package_id">
                                            <option value="0">Только регистрация - ${{ env('REGISTRATION_FEE') }}</option>
                                            @foreach(\App\Models\Package::where('status',1)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }} - ${{ $item->cost+env('REGISTRATION_FEE') }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="error-message"></div>
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
    </script>
@endpush

