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
                    <h3 class="text-themecolor m-b-0 m-t-0">Города</h3>
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
                    <div class="card">
                        <div class="card-block">
                            <form action="{{url('city')}}" method="POST" class="form-horizontal user_create">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-md-12">Название</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('title') }}" name="title" class="form-control form-control-line">
                                        @if ($errors->has('title'))
                                            <span class="text-danger"><small>{{ $errors->first('title') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-md-12" for="position">Город</label>
                                    <div class="col-md-12">
                                        <select class="custom-select form-control required" id="country_id" name="country_id">
                                            @foreach($countries as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="error-message"></div>
                                </div>
                                <hr>
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

@endpush

