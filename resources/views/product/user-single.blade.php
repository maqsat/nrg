@extends('layouts.landing')
@section('custom_style')
    <style>

        .breadcrumb-list > li {
            font-size: 14px;
            list-style: none;
            display: inline;
        }
        .breadcrumb-list > li a:after {
            content: "/";
            vertical-align: middle;
            margin: 0 5px;
            color: #7a7a7a;
        }
        .action-wishlist:hover,
        .action-wishlist:focus{
            color:#fff;
        }
        .add-to-cart.action-wishlist {
            width: 50px;
            text-align: center;
            padding: 0;
        }
        .add-to-cart.action-wishlist i {
            margin-right: 0px;
        }
        .product-add-to-cart .cart-title,
        .product-add-to-cart .cart-title:hover,
        .product-list-action .cart-title,
        .product-list-action .cart-title:hover {
            background-color: transparent;
            border-bottom: none;
            color: inherit;
        }
        .product-add-to-cart .pro-add-btn i,
        .product-list-action .pro-add-btn i {
            margin-right: 10px;
            font-size: 18px;
        }
        .add-to-cart {
            display: inline-block;
        }
        .action-wishlist:hover,
        .action-wishlist:focus{
            color:#fff;
        }
        .add-to-cart.action-wishlist i {
            margin-right: 0px;
        }
        .product-add-to-cart {
            float: none;
        }
        .single-product-wishlist{
            display: inline-block;
            position: relative;
            margin-left: 20px;
        }
        .product-thumbnail .owl-nav  {display: none;}
        .breadcrumb-area {
            padding: 30px 0;
            background-color: #f3f3f3;
        }
        .breadmome-name {
            color: #ff6a00;
            font-size: 24px;
            font-weight: 500;
            text-transform: capitalize;
            margin: 0 0 18px;
        }
        .breadcrumb-content > ul > li {
            display: inline-block;
            list-style: none;
            position: relative;
            font-size: 14px;
            color: #333;
        }
        .breadcrumb-content > ul > li.active{
            color: #ff6a00;
        }
        .breadcrumb-content > ul > li:after {
            content: "/";
            vertical-align: middle;
            margin: 0 5px;
            color: #7a7a7a;
        }
        .breadcrumb-content > ul > li:last-child:after{
            display: none;
        }
        .mt-80 { margin-top: 80px }.mb-80 { margin-bottom: 80px }
        .single-product-name {
            font-size: 22px;
            text-transform: capitalize;
            font-weight: 900;
            color: #444;
            line-height: 24px;
            margin-bottom: 15px;
        }
        .single-product-reviews {
            margin-bottom: 10px;
        }
        .single-product-price {
            margin-top: 25px;
        }
        .single-product-action {
            margin-top: 30px;
            padding-bottom: 30px;
            border-top: 1px solid #ebebeb;
            border-bottom: 1px solid #ebebeb;
            float: left;
            width: 100%;
        }
        .product-discount {
            display: inline-block;
            margin-bottom: 20px;
        }
        .product-discount span.price {
            font-size: 28px;
            font-weight: 900;
            line-height: 30px;
            display: inline-block;
            color: #008bff;
        }
        .product-info {
            color: #333;
            font-size: 14px;
            font-weight: 400;
        }
        .product-info p {
            line-height: 30px;
            font-size: 14px;
            color: #333;
            margin-top: 30px;
        }
        .product-add-to-cart span.control-label {
            display: block;
            margin-bottom: 10px;
            text-transform: capitalize;
            color: #232323;
            font-size: 14px;
        }
        .product-add-to-cart {
            overflow: hidden;
            margin: 20px 0px;
            float: left;
            width: 100%;
        }
        .cart-plus-minus-box {
            border: 1px solid #e1e1e1;
            border-radius: 0;
            color: #3c3c3c;
            height: 49px;
            text-align: center;
            width: 50px;
            padding: 5px 10px;
        }
        .product-add-to-cart .cart-plus-minus {
            margin-right: 25px;
        }
        .cart-plus-minus {
            position: relative;
            width: 75px;
            float: left;
            padding-right: 25px;
        }
        .add-to-cart {
            background: #008bff;
            border: 0;
            border-bottom: 3px solid #0680e5;
            color: #fff;
            box-shadow: none;
            padding: 0 30px;
            border-radius: 3px;
            font-weight: 400;
            cursor: pointer;
            font-size: 14px;
            text-transform: capitalize;
            height: 50px;
            line-height: 50px;
        }
        .add-to-cart:hover {
            background: #ff6a00;
            border-color: #e96405;
        }
    </style>

    <link rel="stylesheet" href="{{asset('css/smoothproducts.css')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
@endsection
@section('content')
    <div class="page-wrapper">

        <div class="wrapper">
            <div class="breadcrumb-wrapper">
                <div class="breadcrumb-area breadcrumbs overlay-bg">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="breadcrumb-content text-center">
                                    <nav class="" role="navigation" aria-label="breadcrumbs">
                                        <ul class="breadcrumb-list">
                                            <li><a href="/">Главная</a></li>
                                            <li><a href="/main-store">Магазин</a><span>{{ $product->title }}</span></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <main>
                <div id="shopify-section-product-template" class="shopify-section">
                    <div class="single-product-area mt-80 mb-80">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-5">
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
                                    <div class="single-product-content">

                                        <div class="product-details">
                                            <h1 class="single-product-name">{{ $product->title }}</h1>
                                            <div class="single-product-reviews">
                                                <span class="shopify-product-reviews-badge" data-id="1912078270534"></span>
                                            </div>
                                            <div class="product-sku">SKU: <span class="variant-sku">YQT71020193</span></div>
                                            <div class="single-product-price">
                                                <div class="product-discount"><span  class="price" id="ProductPrice"><span class=money>${{ $product->cost }}</span></span></div>
                                            </div>
                                            <div class="product-info">{!! $product->description !!}</div>

                                            <div class="single-product-action">
                                                <div class="product-add-to-cart">
                                                    <div class="add">
                                                        <button onclick="addBasket({{ $product->id }},@auth{{ Auth::user()->id }}@else 0 @endauth ,true)"  class="add-to-cart ajax-spin-cart" id="AddToCart">
                                                            <i class="ion-bag"></i>
                                                            <span    class="list-cart-title cart-title" id="AddToCartText">Добавить в корзину</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style type="text/css">.product-details .countdown-timer-wrapper{display: none !important;}</style>
                </div>
            </main>
        </div>

    @include('layouts.footer')
    <!-- ============================================================== -->
    </div>
@endsection

@push('scripts')
    <script src="/monster_admin/main/js/toastr.js"></script>
    <script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>
    <script type="text/javascript" src="{{asset('js/smoothproducts.min.js')}}"></script>
    <script type="text/javascript">
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
            document.getElementById('AddToCartText').innerHTML = "Добавить еще раз"
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