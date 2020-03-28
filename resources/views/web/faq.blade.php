@extends('layouts.landing')

@section('content')
    <div class="container">

        <div class="row section-row">
            <div class="col-md-12 faq" id="accordion">
                <p class="title-box">Вопросы и ответы</p>
                @foreach($faq as $item)
                    <div class="faq-item">

                        <div style="margin-top: 10px;border-bottom: 1px solid rgba(0,0,0,.125);" class="card-header" role="tab" id="headingOne0">
                            <h5 style="line-height: 18px;font-size: 21px;font-weight: 400;" class="mb-0">
                                <a style="color:#0275d8;" data-toggle="collapse" data-parent="#accordion2" href="#collapse-{{$item->id}}" aria-expanded="true" aria-controls="collapseOne0" class="">
                                    <b>{{$item->question}}</b>
                                </a>
                            </h5>
                        </div>

                        <!--<div class="ask">
                            <button style="white-space: normal;" class="btn" data-parent="#accordion" type="button" data-toggle="collapse" data-target="#collapse-{{$item->id}}">{{$item->question}}</button>
                        </div>-->
                        <div style="padding: 7px 0;" class="answer">
                            <div class="collapse" id="collapse-{{$item->id}}">
                                <div class="card">
                                    <div class="card-body">
                                        {!! $item->answer !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection