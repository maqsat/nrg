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
                                            <a href="/pay-prepare?type=manual&@if(!is_null($basket))basket={{ $basket->id }} @endif" class="btn btn-success m-t-10">Оплатить ${{ $all_cost }}</a>
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
                                            <a href="/pay-prepare?type=paypost&@if(!is_null($basket))basket={{ $basket->id }}@endif" class="btn btn-success m-t-10">Оплатить ${{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="https://opencartforum.com/screenshots/monthly_2018_11/robokassa.thumb.png.b405b854136ced060d31d9a19ad41189.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Robokassa</h4>
                                            <p class="card-text">Поддерживает все карты Visa и Master Card</p>
                                            <a href="/pay-prepare?type=robokassa&@if(!is_null($basket))basket={{ $basket->id }}@endif" class="btn btn-success m-t-10">Оплатить ${{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="https://makoli.com/wp-content/uploads/payeer-logo.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Payeer</h4>
                                            <p class="card-text">Оплачивайте через электронный кашелек</p>
                                            <a href="/pay-prepare?type=payeer&@if(!is_null($basket))basket={{ $basket->id }}@endif" class="btn btn-success m-t-10">Оплатить ${{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="/nrg/paybox.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Paybox</h4>
                                            <p class="card-text">Поддерживает все карты Visa и Master Card</p>
                                            <a href="/pay-prepare?type=paybox&@if(!is_null($basket))package={{ $basket->id }}@endif" class="btn btn-success m-t-10">Оплатить ${{ $all_cost }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="https://indigo24.com/img/logo.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">indigo24</h4>
                                            <p class="card-text">Отечественный электронный кашелек</p>
                                            <a href="/pay-prepare?type=indigo&@if(!is_null($basket))package={{ $basket->id }}@endif" class="btn btn-success m-t-10">Оплатить ${{ $all_cost }}</a>
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
