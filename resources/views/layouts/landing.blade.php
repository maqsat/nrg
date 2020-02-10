<!DOCTYPE html>
<html>
<head>
    <title>Главная - ENRISE</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="/website/css/libs.min.css">
    <link rel="stylesheet" href="/website/css/aos.css">
    <link rel="stylesheet" href="/website/css/aos.css">
    <link rel="stylesheet" href="/website/css/all.css">
    <link rel="stylesheet" href="/website/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="/website/css/main.css">
    <link rel="icon" type="image/svg+xml" href="/website/img/logo/favicon.svg">
    @yield("custom_style")
    <script src="//code.jivosite.com/widget.js" data-jv-id="Kcw5NnHs8G" async></script>
</head>
<body class="@yield('body-class')">
<div class="header">
    <nav class="navbar menu">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed pull-left" type="button" data-toggle="collapse"
                        data-target="#alignment-example" aria-expanded="false"><span
                            class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span
                            class="icon-bar"></span><span class="icon-bar"></span></button>
                <div class="visible-sm visible-xs pull-right r-head mobile">
                    <span class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false" id="link-lang">RU</a>
                        <ul class="dropdown-menu" aria-labelledby="link-lang">
                            <li><a class="active" href="">RU</a></li>
                            <li><a href="">KZ</a></li>
                            <li><a href="">ENG</a></li>
                        </ul>
                    </span>
                    @auth
                        <span class="dropdown">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false"
                               id="link-lang">{{auth()->user()->name}}</a>
                            <ul class="dropdown-menu" aria-labelledby="link-lang">
                                <li>
                                    <a class="active" href="{{ url('/logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти</a>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </span>
                        <a href="/basket" class="basket">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge">1</span>
                        </a>
                    @else
                    <span>
                        <a class="btn-blue btn-usr" href="/login">Вход</a>
                    </span>
                    @endauth
                </div>
                <a class="navbar-brand" href="/"><img src="/website/img/logo/logo.png"></a>
            </div>
            <div class="collapse navbar-collapse menu-li" id="alignment-example">
                <div class="navbar-right r-head hidden-xs hidden-sm" @auth style="padding-top: 15px" @endauth>
                    <span class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown"
                                              aria-haspopup="true" aria-expanded="false" id="link-lang">RU</a>
                <ul class="dropdown-menu" aria-labelledby="link-lang">
                  <li><a class="active" href="">RU</a></li>
                  <li><a href="">KZ</a></li>
                  <li><a href="">ENG</a></li>
                </ul>
                    </span>
                    <span class="fs-16"><a href="tel:+7 (727) 339-89-89">+7 (727)<b
                                    class="text-blue">  339-89-89</b></a><small class="d-block"><a href="">Свяжитесь с нами</a></small></span>
                    @auth
                        <span class="dropdown">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false"
                               id="link-lang">{{auth()->user()->name}}</a>
                            <ul class="dropdown-menu" aria-labelledby="link-lang">
                                <li>
                                    <a class="active" href="{{ url('/logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти</a>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </span>
                        <a href="/basket" class="basket">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge">1</span>
                        </a>
                    @else
                        <span><a class="btn-blue btn-usr" href="/login">Вход</a></span>
                    @endauth
                </div>
                <ul class="nav navbar-nav">
                    <li @if(Request::is('/')) class="active" @endif><a href="/">Главная</a></li>
                    <li @if(Request::is('about')) class="active" @endif><a href="/about">О компании</a></li>
                    <li @if(Request::is('news')) class="active" @endif><a href="/getnews">Новости</a></li>
                    <li @if(Request::is('products')) class="active" @endif><a href="/products">О продукции</a></li>
                    <li><a href="/main-store">Магазин</a></li>
                    <li @if(Request::is('cert')) class="active" @endif><a href="/cert">Сертификаты</a></li>
                    <li @if(Request::is('faq')) class="active" @endif><a href="/faq">FAQ</a></li>
                    <li @if(Request::is('contact')) class="active" @endif><a href="/contact">Контакты</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<div class="content">
    @yield('content')
</div>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3"><a href=""><img class="footer-logo" src="/website/img/logo/logo-w.png"></a></div>
            <div class="col-md-3">
                <ul>
                    <li><b>Продукты</b></li>
                    <li><a href="/products#pr1">Energy Rise</a></li>
                    <li><a href="/products#pr2">Energy Max</a></li>
                    <li><a href="/products#pr3">Energy Bar</a></li>
                    <li><a href="/products#pr4">Extra tonus</a></li>
                    <li><a href="/products#pr5">Extra tonus(BANANA)</a></li>
                    <li><a href="/products#pr6">Omega plus</a></li>
                    <li><a href="/products#pr7">Opti Phlex</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <ul>
                    <li><b>Компания</b></li>
                    <li><a href="/about">О компании</a></li>
                    <li><a href="/getnews">Новости</a></li>
                    <li><a href="/login">Бизнес-партнерам</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <div class="ic-social"><a href=""><i class="icons ic-ins"></i></a><a href=""><i class="icons ic-vk"></i></a><a
                            href=""><i class="icons ic-fb"></i></a></div>
                <ul>
                    <li><b>Контакты</b></li>
                    <li><a href="">Алматы</a></li>
                    <li><a href="">Астана</a></li>
                    <li><a href="">Актобе</a></li>
                    <li><a href="">Актау</a></li>
                    <li><a href="">Чимкент</a></li>
                    <li><a href="">Все</a></li>
                </ul>
                <p>© 2019 Разработка информационной системы <a href="https://bgpro.kz" target="_blank" title="Разработка информационной системы">bugin.soft</a></p>
                <p class="copyright">2019 ENRISE Все права защищены</p>
            </div>
        </div>
    </div>
</div>
<script src="/website/js/lib.min.js"></script>
<script src="/website/js/aos.js"></script>
<script src="/website/js/common.js"></script>
@stack('scripts')
</body>
</html>