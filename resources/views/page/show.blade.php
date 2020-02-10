@extends('layouts.landing')

@section('content')
    <section class="top-box">
        <div class="container">
            <h2 class="block-title">{{ $page->title }}</h2>
            <div class="row">
                <div class="col-md-12">
                    <div class="">
                        <img src="{{ $page->preview }}" alt="" style="width: 100%;margin-bottom: 20px;max-height: 500px;object-fit: cover;">
                    </div>
                    <div class="top-item-caption">
                        <p>{!! $page->content !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection