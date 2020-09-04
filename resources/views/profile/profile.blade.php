@extends('layouts.profile')

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
                    <h3 class="text-themecolor m-b-0 m-t-0">{{ __('app.profile') }}</h3>
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
            <!-- Row -->
            <div class="row">
                <!-- Column -->
                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card">
                        <div class="card-block">
                            <center class="m-t-30">
                                <img src="{{Auth::user()->photo}}" class="img-circle profile-img" width="150" />

                                <form action="/updateAvatar" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div style="background-color: #7460ee;height: 20px;margin: 0 auto;width: 1.5px;">
                                    </div>
                                    <label class="btn btn-primary label-img">
                                        <input style='display: none;' type="file" name="avatar" onchange="this.form.submit();">
                                        <i class="fa fa-plus"></i>
                                    </label>
                                </form>
                                <h4 class="card-title m-t-10">{{ Auth::user()->login }}</h4>
                                <h6 class="card-subtitle">{{ Auth::user()->name }}</h6>
                                <div class="row text-center justify-content-md-center">
                                    <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-medium">{{ count($list) }}</font></a></div>
                                    <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-wallet"></i> <font class="font-medium">{{ $balance }}</font></a></div>
                                </div>
                            </center>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-8 col-xlg-9 col-md-7">
                    <div class="card">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active settings" id="settings" role="tabpanel">
                                <div class="card-block">
                                    <form action="/updateProfile" method="POST" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.name') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->name }}" name="name" class="form-control form-control-line">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger"><small>{{ $errors->first('name') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.number') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->number }}" name="number" class="form-control form-control-line">
                                                @if ($errors->has('number'))
                                                    <span class="text-danger"><small>{{ $errors->first('number') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.email') }}</label>
                                            <div class="col-md-12">
                                                <input type="email" value="{{ Auth::user()->email }}" name="email" class="form-control form-control-line">
                                                @if ($errors->has('email'))
                                                    <span class="text-danger"><small>{{ $errors->first('email') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="package_id"  class="col-md-12">Выберите пол:</label>
                                            <div class="col-md-12">
                                                <select class="custom-select form-control required" id="gender" name="gender">
                                                    <option>Не указан</option>
                                                    <option value="1"  @if(old('gender',Auth::user()->gender) == 1) selected @endif>Мужской</option>
                                                    <option value="2"  @if(old('gender',Auth::user()->gender) == 2) selected @endif>Женский</option>
                                                </select>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.birthday') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->birthday }}" name="birthday" class="form-control form-control-line">
                                                @if ($errors->has('birthday'))
                                                    <span class="text-danger"><small>{{ $errors->first('birthday') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12">{{ __('app.country') }}</label>
                                            <div class="col-sm-12">
                                                <select class="form-control form-control-line" name="country_id">
                                                    @foreach(\App\Models\Country::all() as $item)
                                                        <option value="{{ $item->id }}"  @if(Auth::user()->country_id == $item->id) selected @endif>{{ $item->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12">{{ __('app.city') }}</label>
                                            <div class="col-sm-12">
                                                <select class="form-control form-control-line" name="city_id">
                                                    @foreach(\App\Models\City::all() as $item)
                                                        <option value="{{ $item->id }}"  @if(Auth::user()->city_id == $item->id) selected @endif>{{ $item->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.address') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->address }}" name="address" class="form-control form-control-line">
                                                @if ($errors->has('address'))
                                                    <span class="text-danger"><small>{{ $errors->first('address') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.bank') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->bank }}" name="bank" class="form-control form-control-line">
                                                @if ($errors->has('bank'))
                                                    <span class="text-danger"><small>{{ $errors->first('bank') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.card') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->card }}" name="card" class="form-control form-control-line">
                                                @if ($errors->has('card'))
                                                    <span class="text-danger"><small>{{ $errors->first('card') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.password') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="" name="password" class="form-control form-control-line">
                                                @if ($errors->has('password'))
                                                    <span class="text-danger"><small>{{ $errors->first('password') }}</small></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button class="btn btn-success">Update Profile</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>
            <!-- Row -->
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right sidebar -->
            <!-- ============================================================== -->
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @include('layouts.footer')
        <!-- ============================================================== -->
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection
@push('scripts')
    <style>

    </style>
@endpush
