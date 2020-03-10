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
                    <h3 class="text-themecolor m-b-0 m-t-0">Пакет</h3>
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
                            <form action="{{url('package', [$package->id])}}" method="POST" class="form-horizontal form-material">
                                {{ method_field('PATCH') }}
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-md-12">Название</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $package->title }}" name="title" class="form-control form-control-line">
                                        @if ($errors->has('title'))
                                            <span class="help-block"><small>{{ $errors->first('title') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Цена</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $package->cost }}" name="cost" class="form-control form-control-line">
                                        @if ($errors->has('cost'))
                                            <span class="help-block"><small>{{ $errors->first('cost') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Балл(PV)</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $package->pv }}" name="pv" class="form-control form-control-line">
                                        @if ($errors->has('pv'))
                                            <span class="help-block"><small>{{ $errors->first('pv') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Товары</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $package->goods }}" name="goods" class="form-control form-control-line">
                                        @if ($errors->has('goods'))
                                            <span class="help-block"><small>{{ $errors->first('goods') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Виды дохода</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $package->income }}" name="income" class="form-control form-control-line">
                                        @if ($errors->has('income'))
                                            <span class="help-block"><small>{{ $errors->first('income') }}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-md-12" for="position">Дается статус:</label>
                                    <div class="col-md-12">
                                        <select class="custom-select form-control required" id="rank" name="rank">
                                            @foreach(\App\Models\Status::all() as $item)
                                                <option value="{{ $item->id }}" @if(old('rank',$package->rank) == $item->id) selected @endif>{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="error-message"></div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-md-12" for="position">Активный:</label>
                                    <div class="col-md-12">
                                        <select class="custom-select form-control required" id="status" name="status">
                                            <option>Не указан</option>
                                            <option value="0"  @if(old('status',$package->status) == 0) selected @endif>Нет</option>
                                            <option value="1"  @if(old('status',$package->status) == 1) selected @endif>Да</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success" type="submit">Update</button>
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

