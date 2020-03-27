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
                    <h3 class="text-themecolor m-b-0 m-t-0">Трансфер структуры - {{ $user->name }}</h3>
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
                            <form action="/user/transfer" method="POST" class="form-horizontal user_create">
                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" value="{{ $id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="m-t-10">Менеджер</label>
                                        <div class="input-group">
                                            <select class="form-control form-control-line select2" name="inviter_id"  onchange="getSponsorUsers(this)">
                                                <option>Выберите менеджера</option>
                                                @foreach($users as $item)
                                                    <option value="{{ $item->id }}"  @if(old('inviter_id',$user->inviter_id) == $item->id) selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="m-t-10">Закреплен за(показывается только свободные позиции)</label>
                                        <div class="input-group">
                                            <select class="form-control form-control-line select2" name="sponsor_id" id="sponsor_users"  onchange="getPosition(this)">
                                                <option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
                                                @foreach($sponsor_users_list as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="m-t-10">Позиция размещение</label>
                                        <div class="input-group">
                                            <select class="form-control form-control-line" name="position" id="sponsor_positions">
                                                <option value="1" @if($user->position == 1) selected @endif>Слева</option>
                                                <option value="2" @if($user->position == 2) selected @endif>Справа</option>
                                            </select>
                                        </div>
                                    </div>
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

    <script>
        function getSponsorUsers(inviter_id) {
            $.ajax({
                type: "GET",
                url: "/sponsor_users",
                data: 'inviter_id='+inviter_id.value,
                success: function (data) {
                    console.log('Submission was successful.');
                    console.log(data);

                    $('#sponsor_users')
                        .find('option')
                        .remove()
                        .end()
                        .append(data)
                        .val('whatever');

                    $('#sponsor_positions')
                        .find('option')
                        .remove();

                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        }

        function getPosition(sponsor_id) {
            $.ajax({
                type: "GET",
                url: "/sponsor_positions",
                data: 'sponsor_id='+sponsor_id.value,
                success: function (data) {
                    console.log('Submission was successful.');
                    console.log(data);

                    $('#sponsor_positions')
                        .find('option')
                        .remove()
                        .end()
                        .append(data)
                        .val('whatever')
                    ;

                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        }

        function getOffices(city_id) {
            $.ajax({
                type: "GET",
                url: "/user_offices",
                data: 'city_id='+city_id.value,
                success: function (data) {
                    console.log('Submission was successful.');
                    console.log(data);

                    $('#user_offices')
                        .find('option')
                        .remove()
                        .end()
                        .append(data)
                        .val('whatever')
                    ;

                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        }

        function getStatus(status) {
            text = '#status'+status.value;
            status_id = document.getElementById(text).value;
            console.log(status_id);
            $("#status_id").val(status_id);
        }
    </script>
    <script src="/monster_admin//assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/monster_admin/assets/plugins/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function() {
            // For select 2
            $(".select2").select2();
            $('.selectpicker').selectpicker();
        });
    </script>
@endpush

@push('scripts')
    <link href="/monster_admin/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endpush

