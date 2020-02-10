@extends('layouts.admin')

@section('in_content')
    <div class="page-wrapper" style="background: #f2f7f8;">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">

            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Уроки</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                    <a href="/{{$courses->id}}/lessons/create" class="btn pull-right hidden-sm-down btn-success"><i class="mdi mdi-plus-circle"></i> Добавить</a>
                </div>
            </div>

            <!--News new theme-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="table-responsive">
                                    <table class="table color-table success-table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Заголовок</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($courses->lessons as $key => $item)
                                            <tr>
                                                <td> {{ $key + 1 }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td class="actions">

                                                    
                                                    <a href="/{{$courses->id}}/lessons/{{$item->id}}/edit" class="btn btn-success"><i class="mdi mdi-grease-pencil"></i></a>
                                                    <form action='/{{$courses->id}}/lessons/{{ $item->id }}' method='post'>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" onclick="return deleteAlert();"><i class="mdi mdi-delete"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection