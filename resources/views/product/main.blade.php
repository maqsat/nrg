@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">

            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="row button-group">
                                @foreach($tag as $item)
                                    <div class="col-lg-2 col-md-4">
                                        <a href="/main-store?tag={{$item->id}}" type="button" class="btn btn-lg btn-block btn-info">{{$item->tag_name}}</a>
                                    </div>
                                @endforeach
                                <!--<div class="col-lg-2 col-md-4">
                                    <button type="button" class="btn btn-lg btn-block btn-info">#Хиты продаж</button>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <button type="button" class="btn btn-lg btn-block btn-warning">#Скидки</button>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <button type="button" class="btn btn-lg btn-block btn-success">#БАД</button>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <button type="button" class="btn btn-lg btn-block btn-primary">#Книги</button>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <button type="button" class="btn btn-lg btn-block btn-danger">#Сумки</button>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <button type="button" class="btn btn-lg btn-block btn-success">#Тренинг</button>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card-columns text">
                        @foreach($list as $item)
                            <div class="card ribbon-wrapper">
                                <div class="ribbon ribbon-bookmark  ribbon-success">{{ $item->partner_cost }} $</div>
                                <div class="ribbon ribbon-bookmark  ribbon-danger">+ {{ $item->cv }} cv</div>
                                <div class="ribbon ribbon-bookmark  ribbon-info">+ {{ $item->qv }} qv</div>
                                <img class="card-img-top img-fluid" src="{{ $item->image1 }}" alt="{{ $item->title }}">
                                <div class="card-block">
                                    <h4 class="card-title">{{ $item->title }}</h4>
                                    <div class="card-text m-b-15">{!! str_limit(strip_tags($item->description), 200) !!}</div>
                                    <button class="btn btn-inverse m-l-10" onclick="addBasket({{ $item->id }},{{ Auth::user()->id }},true)" id="btn{{$item->id}}">Купить</button>
                                    <a href="/product/{{ $item->id }}" class="btn btn-inverse">Подробнее</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
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

@push('scripts')

    <script src="/monster_admin/main/js/toastr.js"></script>
    <script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function addBasket(good,user,increase) {
            $.ajax({
                type:'POST',
                url:'/basket',
                data: {good_id:good, user_id:user,is_increase:increase},
                success:function(data){
                    if(data.status == true){
                        $.toast({
                            heading: 'Товар добавлен в корзину!',
                            text: 'Товар добавлен в корзину! Что бы оплатить перейдите в корзину',
                            position: 'bottom-right',
                            loaderBg:'#ff6849',
                            icon: 'success',
                            hideAfter: 30000,
                            stack: 6
                        });
                    }
                }
            });

            var selector = "btn"+good;
            document.getElementById(selector).innerHTML = "Добавить еще раз"
        }
    </script>

@if (session('status'))
    <script>
        $.toast({
            heading: 'Пустая корзина!',
            text: '{{ session('status') }}',
            position: 'top-right',
            loaderBg:'#ffffff',
            icon: 'error',
            hideAfter: 60000,
            stack: 6
        });
    </script>
@endif
@endpush


@push('styles')
<link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
@endpush