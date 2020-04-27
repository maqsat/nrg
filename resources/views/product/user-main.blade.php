@extends('layouts.landing')


@section('content')
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
            <div class="col-md-6 mx-auto p-5">
                Баланс повторной покупки - {{ $balance }}
            </div>
        </div>

        @foreach($list->chunk(4) as $item)
            <div class="row">
                @foreach($item as $it)
                    <div class="col-md-3 col-sm-6">
                        <div class="product-grid">
                            <div class="product-image" style="height:300px;">
                                <a href="#">
                                    <img style="width: unset; max-height: 300px;" class="pic-1" src="{{ $it->image1 }}">
                                    {{--<img style="width: unset;max-height: 300px;" class="pic-2" src="{{ $it->image2 }}">--}}
                                </a>
                                <ul class="social">
                                    <li><a href="/product/{{ $it->id }}" data-tip="Подробнее"><i
                                                    class="fa fa-search"></i></a></li>
                                    <li><a href="" data-tip="Добавить в корзину"><i class="fa fa-shopping-cart"></i></a>
                                    </li>
                                </ul>
                                <!--<span class="product-new-label">Sale</span>
                                <span class="product-discount-label">50%</span>-->
                            </div>
                            <ul class="rating">
                                <li class="fa fa-star"></li>
                                <li class="fa fa-star"></li>
                                <li class="fa fa-star"></li>
                                <li class="fa fa-star"></li>
                                <li class="fa fa-star"></li>
                            </ul>
                            <div class="product-content">
                                <h3 class="title"><a href="#">{{ $it->title }}</a></h3>
                                <div class="price">{{ $it->cost }} $</div>
                                <button class="add-to-cart"
                                        onclick="addBasket({{ $it->id }},@auth {{Auth::user()->id}} @else 0 @endauth ,true)"
                                        href="">+ добавить в корзину
                                </button>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>

    @endforeach

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

        function addBasket(good, user, increase) {
            @auth
            $.ajax({
                type: 'POST',
                url: '/basket',
                data: {good_id: good, user_id: user, is_increase: increase},
                success: function (data) {
                    if (data.status == true) {
                        console.log("success suke");
                        $.toast({
                            heading: 'Товар добавлен в корзину!',
                            text: 'Товар добавлен в корзину! Что бы оплатить перейдите в корзину',
                            position: 'bottom-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 30000,
                            stack: 6
                        });
                    }
                }
            });
            var selector = "btn" + good;
            // document.getElementById(selector).innerHTML = "Добавить еще раз";
            @else
            window.location.assign("/login");
            @endauth
        }
    </script>

    @if (session('status'))
        <script>
            $.toast({
                heading: 'Пустая корзина!',
                text: '{{ session('status') }}',
                position: 'top-right',
                loaderBg: '#ffffff',
                icon: 'error',
                hideAfter: 60000,
                stack: 6
            });
        </script>
    @endif
@endpush


@section('custom_style')
    <link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>

    <style>
        /********************* shopping Demo-1 **********************/
        .product-grid {
            font-family: Raleway, sans-serif;
            text-align: center;
            padding: 0 0 72px;
            border: 1px solid rgba(0, 0, 0, .1);
            overflow: hidden;
            position: relative;
            z-index: 1;
            margin-bottom: 20px;
        }

        .product-grid .product-image {
            position: relative;
            transition: all .3s ease 0s;
            padding: 15px;
        }

        .product-grid .product-image a {
            display: block
        }

        .product-grid .product-image img {
            width: 100%;
            height: 200px;
            object-fit: contain;
        }

        .product-grid .pic-1 {
            opacity: 1;
            transition: all .3s ease-out 0s
        }

        .product-grid:hover .pic-1 {
            opacity: 1
        }

        .product-grid .pic-2 {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            transition: all .3s ease-out 0s
        }

        .product-grid:hover .pic-2 {
            opacity: 1
        }

        .product-grid .social {
            width: 150px;
            padding: 0;
            margin: 0;
            list-style: none;
            opacity: 0;
            transform: translateY(-50%) translateX(-50%);
            position: absolute;
            top: 60%;
            left: 50%;
            z-index: 1;
            transition: all .3s ease 0s
        }

        .product-grid:hover .social {
            opacity: 1;
            top: 50%
        }

        .product-grid .social li {
            display: inline-block
        }

        .product-grid .social li a {
            color: #fff;
            background-color: #1349d5;
            font-size: 16px;
            line-height: 40px;
            text-align: center;
            height: 40px;
            width: 40px;
            margin: 0 2px;
            display: block;
            position: relative;
            transition: all .3s ease-in-out
        }

        .product-grid .social li a:hover {
            color: #fff;
            background-color: #ef5777
        }

        .product-grid .social li a:after, .product-grid .social li a:before {
            content: attr(data-tip);
            color: #fff;
            background-color: #000;
            font-size: 12px;
            letter-spacing: 1px;
            line-height: 20px;
            padding: 1px 5px;
            white-space: nowrap;
            opacity: 0;
            transform: translateX(-50%);
            position: absolute;
            left: 50%;
            top: -30px
        }

        .product-grid .social li a:after {
            content: '';
            height: 15px;
            width: 15px;
            border-radius: 0;
            transform: translateX(-50%) rotate(45deg);
            top: -20px;
            z-index: -1
        }

        .product-grid .social li a:hover:after, .product-grid .social li a:hover:before {
            opacity: 1
        }

        .product-grid .product-discount-label, .product-grid .product-new-label {
            color: #fff;
            background-color: #ef5777;
            font-size: 12px;
            text-transform: uppercase;
            padding: 2px 7px;
            display: block;
            position: absolute;
            top: 10px;
            left: 0
        }

        .product-grid .product-discount-label {
            background-color: #333;
            left: auto;
            right: 0
        }

        .product-grid .rating {
            color: #FFD200;
            font-size: 12px;
            padding: 12px 0 0;
            margin: 0;
            list-style: none;
            position: relative;
            z-index: -1
        }

        .product-grid .rating li.disable {
            color: rgba(0, 0, 0, .2)
        }

        .product-grid .product-content {
            background-color: #fff;
            text-align: center;
            padding: 12px 0;
            margin: 0 auto;
            position: absolute;
            left: 0;
            right: 0;
            bottom: -59px;
            z-index: 1;
            transition: all .3s
        }

        .product-grid:hover .product-content {
            bottom: 0
        }

        .product-grid .title {
            font-size: 13px;
            font-weight: 400;
            letter-spacing: .5px;
            text-transform: capitalize;
            margin: 0 0 10px;
            transition: all .3s ease 0s
        }

        .product-grid .title a {
            color: #828282
        }

        .product-grid .title a:hover, .product-grid:hover .title a {
            color: #ef5777
        }

        .product-grid .price {
            color: #333;
            font-size: 17px;
            font-family: Montserrat, sans-serif;
            font-weight: 700;
            letter-spacing: .6px;
            margin-bottom: 8px;
            text-align: center;
            transition: all .3s
        }

        .product-grid .price span {
            color: #999;
            font-size: 13px;
            font-weight: 400;
            text-decoration: line-through;
            margin-left: 3px;
            display: inline-block
        }

        .product-grid .add-to-cart {
            color: white;
            font-size: 13px;
            font-weight: 600;
            background-color: #1349D5;
            outline: none;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
        }

        @media only screen and (max-width: 990px) {
            .product-grid {
                margin-bottom: 30px
            }
        }
    </style>
@endsection
