@extends('layouts.landing')

@section('content')
    <div class="container-fluid">
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
                    @foreach($news as $item)
                        <div class="col-md-6">
                            <div class="item-news" data-aos="flip-up">
                                <p class="title-in">{{$item->news_name}}</p>
                                <p>{!!$item->news_desc!!}</p>
                                <button class="btn btn-blue" type="button"><a href="/getnews/{{$item->id}}">Подробнее</a></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection