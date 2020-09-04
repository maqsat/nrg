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

                    @if(!is_null($orders))
                        @if($orders->status == 11)
                            <div class="alert alert-warning">
                                <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Квитанция находится на проверке</h3>
                                Статус модерации:  Квитанция отправлено на проверку <br>
                                Сумма оплаты: ${{ $orders->amount }} <br>
                                @if($orders->package_id != 0)
                                    Выбранный пакет: {{ \App\Models\Package::find($orders->package_id)->title }} <br>
                                @endif
                                Дата отправки: {{ $orders->updated_at }}
                            </div>
                        @elseif($orders->status == 0)
                            <div class="alert alert-warning">
                                <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Провайдер еще не обработал платеж</h3>
                                Статус модерации:  Платеж ожидает оплаты на стороне Провайдера <br>
                                Сумма оплаты: ${{ $orders->amount }} <br>
                                @if($orders->package_id != 0)
                                    Выбранный пакет: {{ \App\Models\Package::find($orders->package_id)->title }} <br>
                                @endif
                                Дата отправки: {{ $orders->updated_at }} <br>
                                Статус:  <a href="/main-store?history=delete" class="btn btn-xs btn-danger">Удалить и начать заново</a>
                            </div>
                        @elseif($orders->status == 12)
                            <div class="alert alert-danger">
                                <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Квитанция отклонена</h3>
                                Статус модерации:  Фейковая квитанция <br>
                                Сумма оплаты: ${{ $orders->amount }} <br>
                                @if($orders->package_id != 0)
                                    Выбранный пакет: {{ \App\Models\Package::find($orders->package_id)->title }} <br>
                                @endif
                                Дата ответа: {{ $orders->updated_at }} <br>
                                Квитанция:  <a href="{{asset($orders->scan)}}" target="_blank" class="btn btn-xs btn-danger">Посмотреть</a>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <h3 class="text-success"><i class="fa fa-exclamation-triangle"></i> Успешно одобрена</h3>
                                Статус модерации:  Успешно одобрена <br>
                                Сумма оплаты: ${{ $orders->amount }} <br>
                                @if($orders->package_id != 0)
                                    Выбранный пакет: {{ \App\Models\Package::find($orders->package_id)->title }} <br>
                                @endif
                                Дата ответа: {{ $orders->updated_at }} <br>
                            </div>
                        @endif
                    @endif

                    <div class="card">
                        <div class="card-block">
                            <div class="row button-group text-center">
                               <h2> Баланс повторной покупки - ${{ $balance }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(is_null($orders) or ($orders->status != 11 and $orders->status != 0))
            <div class="row">
                <div class="col-12">
                    <div class="card-columns text">
                        @foreach($list as $item)

                            <div class="card ribbon-wrapper">
                                <div class="ribbon ribbon-bookmark  ribbon-success">{{ $item->partner_cost }} $</div>
                                {{--<div class="ribbon ribbon-bookmark  ribbon-danger">+ {{ $item->cv }} cv</div>--}}
                                <div class="ribbon ribbon-bookmark  ribbon-info">+ {{ $item->pv }} pv</div>
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
            @endif
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
                            text: 'Товар добавлен в корзину! Что бы оплатить перейдите в <a href="/basket">корзину</a>',
                            position: 'top-right',
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
