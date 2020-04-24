@extends('layouts.admin')

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
                    <h3 class="text-themecolor m-b-0 m-t-0">Изменить товар</h3>
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
            <div class="row">
                <div class="col-lg-12">
                    @foreach ($errors->all() as $error)
                        <span class="help-block"><small>{{ $error }}</small></span>
                    @endforeach
                    <div class="card">
                        <div class="card-block">
                            <form action="{{url('store', [$product->id])}}" method="POST" class="form-horizontal form-material"  enctype="multipart/form-data">
                                {{ method_field('PATCH') }}
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-md-12">Наименование</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('title',$product->title) }}" name="title" class="form-control form-control-line">
                                        @if ($errors->has('title'))
                                            <span class="help-block"><small>{{ $errors->first('title') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Описание</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control form-control-lin"  name="description" rows="6"  id="editor">{{ old('description',$product->description) }}</textarea>
                                    </div>
                                    @if ($errors->has('description'))
                                        <span class="help-block"><small>{{ $errors->first('description') }}</small></span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Цена</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('cost',$product->cost) }}" name="cost" class="form-control form-control-line">
                                        @if ($errors->has('cost'))
                                            <span class="help-block"><small>{{ $errors->first('cost') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Цена для партнеров</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('partner_cost',$product->partner_cost) }}" name="partner_cost" class="form-control form-control-line">
                                        @if ($errors->has('partner_cost'))
                                            <span class="help-block"><small>{{ $errors->first('partner_cost') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Категория</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('category_id',$product->category_id) }}" name="category_id" class="form-control form-control-line">
                                        @if ($errors->has('category_id'))
                                            <span class="help-block"><small>{{ $errors->first('category_id') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Скидка</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('sale',$product->sale) }}" name="sale" class="form-control form-control-line">
                                        @if ($errors->has('sale'))
                                            <span class="help-block"><small>{{ $errors->first('sale') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                {{--<div class="form-group">
                                    <label class="col-md-12">CV</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('cv',$product->cv) }}" name="cv" class="form-control form-control-line">
                                        @if ($errors->has('cv'))
                                            <span class="help-block"><small>{{ $errors->first('cv') }}</small></span>
                                        @endif
                                    </div>
                                </div>--}}
                                <div class="form-group">
                                    <label class="col-md-12">PV</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('pv',$product->pv) }}" name="pv" class="form-control form-control-line">
                                        @if ($errors->has('pv'))
                                            <span class="help-block"><small>{{ $errors->first('pv') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Картинка</label>
                                    <div class="col-md-12 images">
                                        @if(isset($product->image1))
                                            <img src="{{ asset($product->image1) }}">
                                        @endif
                                        <input type="hidden" value="{{ $product->image1 }}" name="image1">
                                        <input type="file" value="{{ $product->image1 }}" name="image1" class="form-control form-control-line">
                                        @if(isset($product->image2))
                                            <img src="{{ asset($product->image2) }}">
                                        @endif
                                        <input type="hidden" value="{{ $product->image2 }}" name="image2">
                                        <input type="file" value="{{ $product->image2 }}" name="image2" class="form-control form-control-line">
                                        @if(isset($product->image1))
                                            <img src="{{ asset($product->image3) }}">
                                        @endif
                                        <input type="hidden" value="{{ $product->image3 }}" name="image3">
                                        <input type="file" value="{{ $product->image3 }}" name="image3" class="form-control form-control-line">
                                        @if ($errors->has('image1'))
                                            <span class="help-block"><small>{{ $errors->first('image1') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success" type="submit">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @include('layouts.footer')
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection

@push('scripts')
@if (session('status'))
    <script>
        $.toast({
            heading: 'Результат действии',
            text: '{{ session('status') }}',
            position: 'top-left',
            loaderBg:'#ffffff',
            icon: 'warning',
            hideAfter: 30000,
            stack: 6
        });
    </script>
@endif


<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=5y85yv1z81uofzj5d6j38pqqxzrxddxqym36d3n7kv5c5ejy"></script>

<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'image code',
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',

        // without images_upload_url set, Upload tab won't show up
        images_upload_url: '/upload.php',
        convert_urls: false,
        // override default upload handler to simulate successful upload
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '/upload.php');

            xhr.onload = function() {
                var json;

                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                success(json.location);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        },
    });
</script>
@endpush

