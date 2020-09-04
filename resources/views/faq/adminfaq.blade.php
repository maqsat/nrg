@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <div class="container-fluid">

            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Вопросы и ответы</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                </div>
            </div>

            <div class="row section-row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-block">
                                        <div id="accordion2" role="tablist" class="minimal-faq" aria-multiselectable="true">
                                            @foreach($faq as $item)
                                            <div class="card m-b-0">
                                                <div class="card-header" role="tab" id="headingOne0">
                                                    <h5 class="mb-0">
                                                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne{{$item->id}}" aria-expanded="false" aria-controls="collapseOne0" class="collapsed">
                                                            <b>{{$item->question}}</b>
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div style="padding: 15px 5px 15px 5px;" id="collapseOne{{$item->id}}" class="collapse" role="tabpanel" aria-labelledby="headingOne0" aria-expanded="false" style="">
                                                    {!! $item->answer !!}
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                        </div>


            </div>
        </div>
    @include('layouts.footer')
    <!-- ============================================================== -->
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection
