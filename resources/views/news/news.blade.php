@extends('layouts.admin')

@section('in_content')
    <div class="page-wrapper" style="background: #f2f7f8;">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">

            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Новости</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                    <a href="/news/create" class="btn pull-right hidden-sm-down btn-success"><i class="mdi mdi-plus-circle"></i> Create</a>
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
                                        <th>Дата</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($news as $key => $item)
                                            <td> {{ $key + 1 }}</td>
                                            <td>{{ $item->news_name }}</td>
                                            <td>{{ $item->news_date }}</td>
                                            <td class="actions">
                                                <a href="/getnews/{{ $item->id }}" target="_blank" class="btn btn-info"><i class="mdi mdi-eye"></i></a>
                                                <a href="/news/{{ $item->id }}/edit" class="btn btn-success"><i class="mdi mdi-grease-pencil"></i></a>
                                                <form action='/news/{{ $item->id }}' method='post'>
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
            <!--News end new theme-->

         <!--   <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <a href="/news/create" style="float: right">
                                <button class="btn btn-primary box-add-btn">Добавить новость</button>
                            </a>
                            <div class="clear-float"></div>
                        </div>
                        <div>
                            <div style="text-align: right" class="form-group col-md-6" >
                                <h4 class="box-title box-delete-click">
                                    <a href="javascript:void(0)" onclick="deleteAll('news')">Удалить отмеченные</a>
                                </h4>
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="news_datatable" class="table table-bordered table-striped">
                                <thead>
                                <tr style="border: 1px">
                                    <th style="width: 30px">№</th>
                                    <th>Картинка</th>
                                    <th>Заголовок</th>
                                    <th>Текс</th>
                                    <th>Описание</th>
                                    <th>Дата</th>
                                    <th style="width: 15px"></th>
                                    <th style="width: 15px"></th>
                                    <th class="no-sort" style="width: 0px; text-align: center; padding-right: 16px; padding-left: 14px;" >
                                        <input onclick="selectAllCheckbox(this)" style="font-size: 15px" type="checkbox" value="1"/>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>

                                @foreach($news as $key => $val)

                                    <tr>
                                        <td> {{ $key + 1 }}</td>
                                        <td>
                                            <div class="object-image">
                                                <img src="{{$val->news_image}}">
                                            </div>
                                            <div class="clear-float"></div>
                                        </td>
                                        <td>
                                            <a target="_blank" href="/news/{{$val->id}}">
                                                {{ $val['news_name']}}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $val['news_text']}}
                                        </td>
                                        <td>
                                            {{ $val['news_desc']}}
                                        </td>
                                        <td>
                                            {{ $val['news_date']}}
                                        </td>
                                        <td style="text-align: center">
                                                <form action='/news/{{ $val->id }}' method='post'>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"><i class="fa fa-trash-o"></i></button>
                                                </form>
                                        </td>
                                        <td style="text-align: center">
                                            <a href="/admin/news/{{ $val->id }}/edit">
                                                <li class="fa fa-pencil" style="font-size: 20px;"></li>
                                            </a>
                                        </td>
                                        <td style="text-align: center;">
                                            <input class="select-all" style="font-size: 15px" type="checkbox" value="{{$val->id}}"/>
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                            <div style="text-align: center">

                            </div>

                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </div>

@endsection