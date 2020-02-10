@extends('layouts.landing')

@section('content')
    <div class="container-fluid">
        <div class="row section-row">
            <div class="container">
                <div class="row fs-18">
                    <div class="col-sm-12" data-aos="zoom-out-right">
                        <img style="height:100%;" class="i-box-img" src="/{{$news->news_image}}">
                        <p>
                            <b class="text-blue">{{$news->news_name}}</b>
                        <p>{!!$news->news_text!!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection