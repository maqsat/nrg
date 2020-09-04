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
                    <h3 class="text-themecolor m-b-0 m-t-0">Редактор баланса пользователя - {{ $user->name }}</h3>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-block">
                            <form method="POST" class="form-horizontal user_create">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <label  class="m-t-10" for="description">Описание пополнения:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" required name="description" id="description">
                                        </div>
                                        <label  class="m-t-10" for="sum">Сумма пополнения($):</label>
                                        <div class="input-group">
                                            <input type="text" value="0" class="form-control" required name="sum" id="sum" />
                                        </div>
                                        <label  class="m-t-10" for="sum">Сумма пополнения(PV):</label>
                                        <div class="input-group">
                                            <input type="number" value="0" min="0" step="50" class="form-control" required name="pv" id="pv" />
                                        </div>
                                        <label  class="m-t-10" for="sum">От кого:</label>
                                        <div class="input-group">
                                            <select name="in_user" class="form-control form-control-line select2" id="in_user">
                                                @foreach($users as $item)
                                                    <option value="{{ $item['id'] }}"{{$item['id'] == $user->id ? ' selected="selected"' : ''}}>{{ $item['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success" type="submit">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-block">
                            <form method="POST" class="form-horizontal user_create">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <label  class="m-t-10" for="description">Причина:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" required name="description" id="description">
                                        </div>
                                        <label  class="m-t-10" for="sum">Сумма(PV):</label>
                                        <div class="input-group">
                                            <input type="number" value="0" min="0" step="50" class="form-control" required name="pv" id="pv" />
                                        </div>
                                        <label  class="m-t-10" for="position">С какой стороны:</label>
                                        <div class="input-group">
                                            <select name="position" id="position" class="form-control form-control-line">
                                                <option value="1">Слева</option>
                                                <option value="2">Справа</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-danger" type="submit">Minus</button>
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
    <script src="/monster_admin//assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <link href="/monster_admin/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <script>
        jQuery(document).ready(function() {
            $(".select2").select2();
        });
    </script>
@endpush

