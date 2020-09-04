@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">{{ $review ? __('app.edit_review') : __('app.add_review') }}</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <!-- Row -->
            <div class="row">
                <!-- Column -->
                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card">
                        <div class="card-block">
                            <center class="m-t-30">
                                <img src="{{ $review && $review->image ? Storage::url($review->image) : '/images/no-image.png' }}" class="profile-img" width="150" />

                                <form action="{{ $review ? route('updateReviewImage', ['review_id' => $review->id] ) : route('updateReviewImage') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div style="background-color: #7460ee;height: 20px;margin: 0 auto;width: 1.5px;">
                                    </div>
                                    <label class="btn btn-primary label-img">
                                        <input style='display: none;' type="file" name="avatar" onchange="this.form.submit();">
                                        <i class="fa fa-plus"></i>
                                    </label>
                                </form>
                            </center>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-8 col-xlg-9 col-md-7">
                    <div class="card">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active settings" id="settings" role="tabpanel">
                                <div class="card-block">
                                    <form action="{{ $review ? route('review', ['review_id' => $review->id]) : route('review')}}" method="POST" class="form-horizontal">
                                        @csrf
                                        <div class="form-group">
                                            <label class="col-md-12" for="product">{{ __('app.product') }}</label>
                                            <div class="col-md-12">
                                                <select name="product_id" id="product" class="form-control form-control-line">
                                                    @foreach($products as $product)
                                                        <option value="{{$product->id}}" @if($review && $product->id === $review->product_id) selected @endif>{{ $product->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('product_id'))
                                                <span class="help-block"><small>{{ $errors->first('product_id') }}</small></span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{__('app.link_youtube')}}</label>
                                            <div class="col-md-12">
                                                <input class="form-control form-control-line" type="text" value="{{ $review ? $review->link_youtube : '' }}" name="link" required>
                                            </div>
                                            @if ($errors->has('link'))
                                                <span class="help-block"><small>{!! $errors->first('link') !!}</small></span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">{{__('app.description')}}</label>
                                            <div class="col-md-12">
                                                <textarea class="form-control form-control-lin" name="description" rows="6"  id="editor">{{ $review ? $review->description : '' }}</textarea>
                                            </div>
                                            @if ($errors->has('description'))
                                                <span class="help-block"><small>{{ $errors->first('description') }}</small></span>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button class="btn btn-success">{{ $review ? __('app.edit_review') : __('app.add_review') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>
            <!-- Row -->
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right sidebar -->
            <!-- ============================================================== -->
            <!-- End Right sidebar -->
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
@push('styles')
    <style>
        .help-block {
            color: red;
            margin-left: 20px;
            display: block;
        }
    </style>
@endpush
@push('scripts')
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.7/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.7/summernote.js"></script>
    <script>
        jQuery(function($) {

            $('#editor').summernote({
                disableDragAndDrop: true,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ]});

        });
    </script>
@endpush
