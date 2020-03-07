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
                            <form action="{{url('user', [$user->id])}}" method="POST" class="form-horizontal form-material">
                                {{ method_field('PATCH') }}
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.name') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $user->name }}" name="name" class="form-control form-control-line">
                                        @if ($errors->has('name'))
                                            <span class="help-block"><small>{{ $errors->first('name') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.number') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $user->number }}" name="number" class="form-control form-control-line">
                                        @if ($errors->has('number'))
                                            <span class="help-block"><small>{{ $errors->first('number') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.email') }}</label>
                                    <div class="col-md-12">
                                        <input type="email" value="{{ $user->email }}" name="email" class="form-control form-control-line">
                                        @if ($errors->has('email'))
                                            <span class="help-block"><small>{{ $errors->first('email') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="package_id"  class="col-md-12">Выберите пол:</label>
                                    <div class="col-md-12">
                                        <select class="custom-select form-control required" id="gender" name="gender">
                                            <option>Не указан</option>
                                            <option value="1"  @if(old('gender',$user->gender) == 1) selected @endif>Мужской</option>
                                            <option value="2"  @if(old('gender',$user->gender) == 2) selected @endif>Женский</option>
                                        </select>
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.birthday') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $user->birthday }}" name="birthday" class="form-control form-control-line">
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
                                                <option value="{{ $item->id }}"  @if($user->countre_id == $item->id) selected @endif>{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12">{{ __('app.city') }}</label>
                                    <div class="col-sm-12">
                                        <select class="form-control form-control-line" name="city_id" >
                                            @foreach(\App\Models\City::all() as $item)
                                                <option value="{{ $item->id }}"  @if($user->city_id == $item->id) selected @endif>{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.address') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $user->address }}" name="address" class="form-control form-control-line">
                                        @if ($errors->has('address'))
                                            <span class="help-block"><small>{{ $errors->first('address') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.bank') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ Auth::user()->bank }}" name="bank" class="form-control form-control-line">
                                        @if ($errors->has('bank'))
                                            <span class="help-block"><small>{{ $errors->first('bank') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">{{ __('app.card') }}</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $user->card }}" name="card" class="form-control form-control-line">
                                        @if ($errors->has('card'))
                                            <span class="help-block"><small>{{ $errors->first('card') }}</small></span>
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
                                    <div class="col-sm-12">
                                        <button class="btn btn-success" type="submit">Update Profile</button>
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
@endpush

