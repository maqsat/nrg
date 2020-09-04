@extends('layouts.admin')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endpush
@section('in_content')
    <div class="page-wrapper" style="background: #f2f7f8;">
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Добавить рекоммендацию</h3>
            </div>
            <div class="col-md-6 col-4 align-self-center">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @if (Session::has('message'))
                    <script>
                        Swal.fire({
                            type: 'success',
                            text: '{{ Session::get("message") }}'
                        })
                    </script>
                @endif
                <div class="card">
                    <div class="card-block">
                        <form action="/recommendations" method="POST" class="form-horizontal form-material"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-12">Название</label>
                                <div class="col-md-12">
                                    <input type="text"  name="title" class="form-control form-control-line">
                                    @if ($errors->has('title'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('title') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12" for="exampleFormControlSelect1">Выберите курс</label>
                                <div class="col-md-12">
                                    <select class="form-control" name="course_id" id="exampleFormControlSelect1">
                                        @foreach($courses as $course)
                                            <option value="{{$course->id}}">{{$course->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Описание</label>
                                <div class="col-md-12">
                                    <input type="text"  name="description" class="form-control form-control-line">
                                    @if ($errors->has('description'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('description') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Превью</label>
                                <div class="col-md-12">
                                    <input type="file" name="file" class="form-control form-control-line">
                                    @if ($errors->has('file'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('file') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Фото</label>
                                <input type="file" name="photo" class="form-control form-control-line">
                                @if ($errors->has('photo'))
                                    <div class="alert alert-danger">
                                        {{ $errors->first('photo') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success" type="submit">Добавить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection


