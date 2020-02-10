@extends('layouts.landing')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="slid-big">
                <div class="item-slid"><img src="/website/img/bg/img1.png" data-aos="fade-right">
                    <div class="top-slid" data-aos="fade-left">
                        <div class="text-prod">
                            <p class="title-slid" data-aos="flip-bown">Продукция компании ENRISE наполняет тело силой и бодростью!</p>
                            <a href="/products"><button class="btn btn-white" type="button" data-aos="fade-up-down">Продукция</button></a>
                        </div>
                        <div class="in-text" >
                            <p>Линейка препаратов «ENRISE» включает в себя серию из 6 основных продуктов, каждый из которых является результатом кропотливого анализа работ</p>
                        </div>
                    </div></div>
                <div class="item-slid"><img src="/website/img/bg/img14.png" data-aos="fade-right">
                    <div class="top-slid" data-aos="fade-left">
                        <div class="text-prod">
                            <p class="title-slid" data-aos="flip-right">Продукция компании ENRISE наполняет тело силой и бодростью!</p>
                            <a href="/products"><button class="btn btn-white" type="button" data-aos="fade-up-down">Продукция</button></a>
                        </div>
                        <div class="in-text" >
                            <p>Линейка препаратов «ENRISE» включает в себя серию из 6 основных продуктов, каждый из которых является результатом кропотливого анализа работ</p>
                        </div>
                    </div></div>
            </div>
        </div>
        <div class="row section-row bg-white">
            <div class="box-grey box-abt">
                <div class="container">
                    <p class="title-box">О нас</p>
                    <div class="row">
                        <div class="col-md-6 fs-18 abt-sec">
                            <div class="bg-abt"></div>
                            <div data-aos="zoom-out-right">
                                <p>Команда компании «ENRISE» убеждена в том, что каждый человек достоин счастья, здоровья и достатка!</p>
                                <p>Поэтому миссия компании «ENRISE» очевидна: мы хотим обеспечить потребителя качественным продуктом для стабильного роста активности как его жизнедеятельности так и финансового благосостояния! Мы дорожим доверием каждого, и потому создание некачественной продукции для нас - неоправданный риск.</p>
                                <a href="/about"><button class="btn btn-blue" type="button">Подробнее</button></a>
                            </div>
                        </div>
                        <div class="col-md-6" data-aos="zoom-out-left">
                            <iframe width="100%" height="315" src="https://www.youtube.com/embed/Ezo1Xwl-GwM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row section-row">
            <div class="container">
                <p class="title-box">Новости</p>
                <div class="row i-box" data-aos="flip-up">
                    <div class="col-md-6 fs-18">
                        <p>Мы хотим помочь Вам начать успешную и продуктивную работу в компании, используя все инструменты, которые рада предложить Вам компания «ENRISE».</p>
                    </div>
                </div>
                <div class="row fs-18">
                    @foreach($news->chunk(2) as $item)
                        <div class="row fs-18">
                            @foreach($item as $it)
                                <div class="col-md-6">
                                    <div class="item-news" data-aos="flip-up">
                                        <p class="title-in">{{$it->news_name}}</p>
                                        <p>{!!$it->news_desc!!}</p>
                                        <button class="btn btn-blue" type="button"><a href="/getnews/{{$it->id}}">Подробнее</a></button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
@endsection