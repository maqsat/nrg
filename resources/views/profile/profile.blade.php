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
                            <hr> </div>
                        <div class="card-block">
                            <small class="text-muted">Email</small>
                            <h6>{{ Auth::user()->email }}</h6>
                            <small class="text-muted p-t-10 db">Телефон</small>
                            <h6>{{ Auth::user()->number }}</h6>
                            <small class="text-muted p-t-10 db">Адрес</small>
                            <h6>{{ Auth::user()->address }}, {{ \App\Models\City::whereId(Auth::user()->city_id)->first()->title }}</h6>
                            <small class="text-muted p-t-10 db">Номер карты</small>
                            <h6>{{ Auth::user()->card }}</h6>
                            <small class="text-muted p-t-10 db">Поделиться</small>
                            <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                            <script src="//yastatic.net/share2/share.js"></script>
                            <div class="ya-share2" data-services="vkontakte,facebook,moimir,twitter,whatsapp,skype,telegram" data-url="https://en-rise.com/" data-title="Продукция компании ENRISE наполняет тело силой и бодростью!" data-size="m"></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-8 col-xlg-9 col-md-7">
                    <div class="card">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs profile-tab" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Последние событии</a> </li>
                            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Данные</a> </li>
                            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Настройки</a> </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="home" role="tabpanel">
                                <div class="card-block">
                                    <div class="profiletimeline">
                                        @forelse($feed as $item)
                                        <div class="sl-item">
                                            <div class="sl-left"> <img src="/monster_admin/assets/images/users/1.jpg" alt="user" class="img-circle" /> </div>
                                            <div class="sl-right">
                                                <div>
                                                    <a href="#" class="link">{{ $item->name }}</a>
                                                    <span class="sl-date">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($item->created_at))->diffForHumans() }}</span>
                                                    <p>Зарегистрировась(-ся) в прогрумму <b>{{ \App\Models\Program::find($item->program_id)->title }}</b></p>
                                                    <div class="like-comm"><a href="javascript:void(0)" class="link m-r-10"><i class="fa fa-heart text-danger"></i> 1 Like</a> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        @empty
                                            <div class="sl-item">
                                                <div class="sl-left"> <img src="/monster_admin/assets/images/users/1.jpg" alt="user" class="img-circle" /> </div>
                                                <div class="sl-right">
                                                    <div>
                                                        <a href="#" class="link">{{ Auth::user()->name }}</a>
                                                        <span class="sl-date">{{ \Carbon\Carbon::createFromTimeStamp(strtotime(Auth::user()->created_at))->diffForHumans() }}</span>
                                                        <p>Вы зарегистрировались в прогрумму <b>{{ \App\Models\Program::find(Auth::user()->program_id)->title }}</b></p>
                                                        <div class="like-comm"><a href="javascript:void(0)" class="link m-r-10"><i class="fa fa-heart text-danger"></i> 1 Like</a> </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <!--second tab-->
                            <div class="tab-pane" id="profile" role="tabpanel">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12 b-r"> <strong>{{ __('app.program') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ \App\Models\Program::find(Auth::user()->program_id)->title }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12"> <strong>{{ __('app.login') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ Auth::user()->login }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12 b-r"> <strong>{{ __('app.sponsor') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ Auth::user()->sponsor }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12"> <strong>{{ __('app.name') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ Auth::user()->name }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12 b-r"> <strong>{{ __('app.iin') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ Auth::user()->iin }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12"> <strong>{{ __('app.number') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ Auth::user()->number }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12 b-r"> <strong>{{ __('app.card') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ Auth::user()->card }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12"> <strong>{{ __('app.email') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ Auth::user()->email }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12 b-r"> <strong>{{ __('app.birthday') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ Auth::user()->birthday }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12"> <strong>{{ __('app.address') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ Auth::user()->address }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-12 b-r"> <strong>{{ __('app.city') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ \App\Models\City::find(Auth::user()->country_id)->title }}</p>
                                        </div>
                                        <div class="col-md-6 col-xs-1"> <strong>{{ __('app.country') }}</strong>
                                            <br>
                                            <p class="text-muted">{{ \App\Models\Country::find(Auth::user()->country_id)->title }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="settings" role="tabpanel">
                                <div class="card-block">
                                    <form action="/updateProfile" method="POST" class="form-horizontal form-material">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.login') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->login }}" name="login" class="form-control form-control-line">
                                                @if ($errors->has('login'))
                                                    <span class="help-block"><small>{{ $errors->first('login') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.name') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->name }}" name="name" class="form-control form-control-line">
                                                @if ($errors->has('name'))
                                                    <span class="help-block"><small>{{ $errors->first('name') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.iin') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->iin }}" name="iin" class="form-control form-control-line">
                                                @if ($errors->has('iin'))
                                                    <span class="help-block"><small>{{ $errors->first('iin') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.number') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->number }}" name="number" class="form-control form-control-line">
                                                @if ($errors->has('number'))
                                                    <span class="help-block"><small>{{ $errors->first('number') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.card') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->card }}" name="card" class="form-control form-control-line">
                                                @if ($errors->has('card'))
                                                    <span class="help-block"><small>{{ $errors->first('card') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.email') }}</label>
                                            <div class="col-md-12">
                                                <input type="email" value="{{ Auth::user()->email }}" name="email" class="form-control form-control-line">
                                                @if ($errors->has('email'))
                                                    <span class="help-block"><small>{{ $errors->first('email') }}</small></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{ __('app.birthday') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" value="{{ Auth::user()->birthday }}" name="birthday" class="form-control form-control-line">
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
