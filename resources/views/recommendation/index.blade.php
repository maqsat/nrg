@extends('layouts.admin')

@section('in_content')
    <div class="page-wrapper" style="background: #f2f7f8;">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">

            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Рекомендации</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                    <a href="/recommendations/create" class="btn pull-right hidden-sm-down btn-success"><i class="mdi mdi-plus-circle"></i> Добавить</a>
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
                                        <th>курс</th>
                                        <th>описание</th>
                                        <th>фото</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recommendations as $key => $item)
                                        <tr>
                                            <td> {{ $key + 1 }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->course["title"] }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td><img style="width:150px;" src="{{ $item->file }}"></td>
                                            <td class="actions">
                                                <a href="/recommendations/{{ $item->id }}/edit" class="btn btn-success"><i class="mdi mdi-grease-pencil"></i></a>
                                                <form action='/recommendations/{{ $item->id }}' method='post'>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return deleteAlert();"><i class="mdi mdi-delete"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $recommendations->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection