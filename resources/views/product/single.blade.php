@extends('layouts.profile')
@push('styles')
    <link rel="stylesheet" href="{{asset('css/smoothproducts.css')}}">
    <style>
        .product_title{
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .info_wrapper{
            padding:20px;
        }
        .price{
            font-size: 20px;
            margin-bottom: 5px;
            color: #222222;
        }
        .cv{
            font-size: 20px;
            margin-bottom: 5px;
            color: #222222;
        }
        .description{
            padding-top: 20px;
            border-top: 1px dotted #d5d5d5;
            margin-top: 20px;
            margin-bottom: 35px;
        }
        .buy_button{
            line-height: 38px;
            padding-right: 30px;
            padding-top:3px;
            padding-bottom:3px;
            text-transform: uppercase;

        }
    </style>
@endpush
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
            <div class="row" style="background-color: #fff;">
                <div class="col-md-4 info_wrapper">
                    <div class="sp-wrap">
                        @if( $product->image1 )
                            <a href="/{{ $product->image1 }}"><img src="/{{ $product->image1 }}" alt=""></a>
                        @endif
                        @if( $product->image2 )
                            <a href="/{{ $product->image2 }}"><img src="/{{ $product->image2 }}" alt=""></a>
                        @endif
                        @if( $product->image3 )
                            <a href="/{{ $product->image3 }}"><img src="/{{ $product->image3 }}" alt=""></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="info_wrapper">
                        <h4 class="product_title">{{ $product->title }}</h4>
                        <div class="price">Цена:<span style="font-weight:bold;color:#0275d8;"> {{ $product->partner_cost }}$</span></div>
                        {{--<div class="cv">CV:<span style="font-weight:bold;color:red;">{{ $product->cv }}cv</span></div>--}}
                        <div class="cv">PV:<span style="font-weight:bold;color:red;"> {{ $product->pv }}pv</span></div>
                        <div class="description">{!! $product->description !!}</div>
                        <button onclick="addBasket({{ $product->id }},{{ Auth::user()->id }},true)" id="btn{{$product->id}}" class="buy_button btn btn-info waves-effect waves-light" type="button"><span class="btn-label"><i class="fa fa-shopping-cart"></i></span>Купить</button>
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
    <script type="text/javascript" src="{{asset('js/smoothproducts.min.js')}}"></script>
    <script type="text/javascript">
        /* wait for images to load */
        $(window).on('load', function(){$('.sp-wrap').smoothproducts();});

    </script>

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
                            text: 'Товар добавлен в корзину! Что бы оплатить перейдите в  <a href="/basket">корзину</a>',
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
