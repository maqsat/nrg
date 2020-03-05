@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">

                @foreach($not_cash_bonuses as $item)
                    @if($item->type == 'travel_bonus')
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Поздравляем, Happy Travel!</h3> За закрытие статусов, начиная с золота, Вы
                            получаете путевку в экзотические страны мира, за счет компании!
                        </div>
                    @endif

                    @if($item->type == 'status_no_cash_bonus')
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Поздравляем, Бонус признания!</h3> За достижение определенного статуса,
                            компания премирует партнера вознаграждением: VIP подарок от компании
                        </div>
                    @endif
                @endforeach

            <div class="row">
                <!-- Column -->
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Информация</th>
                                        <th>Пакет</th>
                                        <th>Статус</th>
                                        <th>Товарооборот</th>
                                        <th>Баланс</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><span class="round"><img src="{{Auth::user()->photo}}" alt="user" width="50" class="home-img" /></span></td>
                                        <td>
                                            <h6>{{ $user->name }}</h6><small class="text-muted">{{ $user->email }}</small></td>
                                        <td>{{ $package->title }}(${{ $package->cost }})</td>
                                        <td>{{ $status->title }}</td>
                                        <td>{{ $pv_counter_all }}  PV</td>
                                        <td>${{ $balance }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Реферальная ссылка</h4>
                            <h6 class="card-subtitle">Партнеры будут распологаться в структуре по выбранному <code>типу размещение</code></h6>
                            <div class="button-group">
                                <a href="/home?default_position=1">
                                    <button type="button" class="btn @if(Auth::user()->default_position == 1) btn-info @else btn-success @endif">@if(Auth::user()->default_position == 1) <i class="fa fa-check"></i> @endifСлева</button>
                                </a>
                                <a href="/home?default_position=0">
                                    <button type="button" class="btn @if(Auth::user()->default_position == 0) btn-info @else btn-success @endif">@if(Auth::user()->default_position == 0) <i class="fa fa-check"></i> @endifАвтоматически</button>
                                </a>
                                <a href="/home?default_position=2">
                                    <button type="button" class="btn @if(Auth::user()->default_position == 2) btn-info @else btn-success @endif">@if(Auth::user()->default_position == 2) <i class="fa fa-check"></i> @endifСправа</button>
                                </a>
                            </div>
                            <div class="input-group m-t-15">
                                <input  class="form-control form-control-line" id="post-shortlink" value="https://nrg.bgpro.kz/register?inviter_id={{ Auth::user()->id }}">
                                <span class="input-group-btn">
                                    <button type="button" id="copy-button" data-clipboard-target="#post-shortlink" class="btn waves-effect waves-light btn-success">Копировать</button>
                                </span>
                            </div>
                            <div class="input-group m-t-15">
                                <script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                                <script src="https://yastatic.net/share2/share.js"></script>
                                <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,viber,whatsapp,skype,telegram" data-title="Реферальная ссылка от {{ Auth::user()->name }}" data-url="https://nrg-max.com/register?inviter_id={{ Auth::user()->id }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Column -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <div class="row p-t-10 p-b-10">
                                <!-- Column -->
                                <div class="col p-r-0">
                                    <h1 class="font-light">{{ count($invite_list) }}</h1>
                                    <h6 class="text-muted">Личники</h6></div>
                                <!-- Column -->
                                <div class="col text-right align-self-center">
                                    <div data-label="20%" class="css-bar m-b-0 css-bar-primary css-bar-20"><i class="mdi mdi-account-circle"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <div class="row p-t-10 p-b-10">
                                <!-- Column -->
                                <div class="col p-r-0">
                                    <h1 class="font-light">{{ $list }}</h1>
                                    <h6 class="text-muted">Все партнеры</h6></div>
                                <!-- Column -->
                                <div class="col text-right align-self-center">
                                    <div data-label="30%" class="css-bar m-b-0 css-bar-danger css-bar-20"><i class="mdi mdi-briefcase-check"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <div class="row p-t-10 p-b-10">
                                <!-- Column -->
                                <div class="col p-r-0">
                                    <h1 class="font-light status-title">{{ $pv_counter_left }}</h1>
                                    <h6 class="text-muted">Левая ветка PV</h6></div>
                                <!-- Column -->
                                <div class="col text-right align-self-center">
                                    <div data-label="40%" class="css-bar m-b-0 css-bar-warning css-bar-40"><i class="mdi mdi-star-circle"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <div class="row p-t-10 p-b-10">
                                <!-- Column -->
                                <div class="col p-r-0">
                                    <h1 class="font-light">{{ $pv_counter_right }}</h1>
                                    <h6 class="text-muted">Правая ветка PV</h6></div>
                                <!-- Column -->
                                <div class="col text-right align-self-center">
                                    <div data-label="60%" class="css-bar m-b-0 css-bar-info css-bar-60"><i class="mdi mdi-star-circle"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>
            <!-- ============================================================== -->
            <!-- End PAge Content -->
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

@push('styles')
    <link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="/monster_admin/assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="/monster_admin/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="/monster_admin/main/js/dashboard1.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js"></script>
    @if (session('status'))

        <script src="/monster_admin/main/js/toastr.js"></script>
        <script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>
        <script>
            $.toast({
                heading: 'Результат запроса',
                text: '{{ session('status') }}',
                position: 'top-right',
                loaderBg:'#ffffff',
                icon: 'warning',
                hideAfter: 60000,
                stack: 6
            });
        </script>
    @endif

    <script>

        (function(){
            new Clipboard('#copy-button');
        })();
    </script>

@endpush
