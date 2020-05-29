@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-block">
                                    <h4 class="card-title">Детали оплаты</h4>
                                    <div class="table-responsive">
                                        <table class="table stylish-table">
                                            <thead>
                                            <tr>
                                                <th style="width:90px;">Product</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><span class="round"><i class="ti-shopping-cart"></i></span></td>
                                                <td>
                                                    <h6><a href="javascript:void(0)" class="link">Регистрационный сбор</a></h6><small class="text-muted">User ID : {{ Auth::user()->id }} </small></td>
                                                <td>
                                                    <h5>1</h5></td>
                                                <td>
                                                    <h5>{{$currency_symbol}}{{ env('REGISTRATION_FEE')*$current_currency }}</h5></td>
                                            </tr>
                                            @if(!is_null($package))
                                                <tr>
                                                    <td><span class="round bg-success"><i class="ti-shopping-cart"></i></span></td>
                                                    <td>
                                                        <h6><a href="javascript:void(0)" class="link">Пакет {{ $package->title }}</a></h6><small class="text-muted">Package ID : {{ $package->id }} </small></td>
                                                    <td>
                                                        <h5>1</h5></td>
                                                    <td>
                                                        <h5>{{$currency_symbol}}{{ $package->cost*$current_currency }}</h5></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td><span class="round bg-primary"><i class="ti-shopping-cart"></i></span></td>
                                                <td>
                                                    <h6><a href="javascript:void(0)" class="link">Всего к оплате</a></h6><small class="text-muted">User ID : {{ Auth::user()->id }} </small>
                                                </td>
                                                @if(!is_null($package))
                                                    <td><h5>2</h5></td>
                                                    <td><h5>{{$currency_symbol}}{{ ($package->cost + env('REGISTRATION_FEE'))*$current_currency }}</h5></td>
                                                    <?php $all_cost = ($package->cost + env('REGISTRATION_FEE'))*$current_currency; ?>
                                                @else
                                                    <td><h5>1</h5></td>
                                                    <td><h5>{{$currency_symbol}}{{ env('REGISTRATION_FEE')*$current_currency }}</h5></td>
                                                    <?php $all_cost = env('REGISTRATION_FEE')*$current_currency; ?>
                                                @endif
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
                            <h4 class="m-b-20">Выберите удобный вид оплаты</h4>
                            <!-- Row -->
                            <div class="row img-for-pay">
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive " src="/nrg/chek.jpeg" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Скан квитанции</h4>
                                            <p class="card-text">Прикрепите Скан квитанции к форме</p>
                                            <a href="/pay-prepare?type=manual&@if(!is_null($package))package={{ $package->id }} @endif" class="btn btn-success m-t-10">Оплатить {{$currency_symbol}}{{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="/nrg/paypost.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">PayPost</h4>
                                            <p class="card-text">В карте должен быть подключен 3D secure</p>
                                            <a href="/pay-prepare?type=paypost&@if(!is_null($package))package={{ $package->id }}@endif" class="btn btn-success m-t-10">Оплатить {{$currency_symbol}}{{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                                {{--<div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="https://opencartforum.com/screenshots/monthly_2018_11/robokassa.thumb.png.b405b854136ced060d31d9a19ad41189.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Robokassa</h4>
                                            <p class="card-text">Поддерживает все карты Visa и Master Card</p>
                                            <a href="/pay-prepare?type=robokassa&@if(!is_null($package))package={{ $package->id }}@endif" class="btn btn-success m-t-10">Оплатить {{$currency_symbol}}{{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>--}}
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="https://makoli.com/wp-content/uploads/payeer-logo.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Payeer</h4>
                                            <p class="card-text">Оплачивайте через электронный кашелек</p>
                                            <a href="/pay-prepare?type=payeer&@if(!is_null($package))package={{ $package->id }}@endif" class="btn btn-success m-t-10">Оплатить {{$currency_symbol}}{{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                                {{--<div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="/nrg/paybox.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Paybox</h4>
                                            <p class="card-text">Поддерживает все карты Visa и Master Card</p>
                                            <a href="/pay-prepare?type=paybox&@if(!is_null($package))package={{ $package->id }}@endif" class="btn btn-success m-t-10">Оплатить {{$currency_symbol}}{{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>--}}
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="https://indigo24.com/img/logo.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">indigo24</h4>
                                            <p class="card-text">Отечественный электронный кашелек</p>
                                            <a href="/pay-prepare?type=indigo&@if(!is_null($package))package={{ $package->id }}@endif" class="btn btn-success m-t-10">Оплатить {{$currency_symbol}}{{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                            </div>
                            <!-- Row -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        @include('layouts.footer')
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection
