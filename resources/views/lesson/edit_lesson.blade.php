@extends('layouts.admin')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endpush
@section('in_content')

    <div class="page-wrapper" style="background: #f2f7f8;">

        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Изменить урок</h3>
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
                        <form action="/{{$course_id}}/lessons/{{$lesson->id}}" method="POST" class="form-horizontal form-material"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @method('put')
                            <input type="hidden" name="course_id" value="{{$course_id}}">
                            <div class="form-group">
                                <input type="text" name="title" class="form-control" placeholder="Название" value="{{$lesson->title}}">
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Видео</label>
                                <input type="file" name="path" class="form-control form-control-line">
                                @if ($errors->has('preview'))
                                    <div class="alert alert-danger">
                                        {{ $errors->first('preview') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Фото</label>
                                <img style="width:150px;" src="/{{$lesson->photo}}">
                                <input type="file" name="photo" class="form-control form-control-line">
                                @if ($errors->has('photo'))
                                    <div class="alert alert-danger">
                                        {{ $errors->first('photo') }}
                                    </div>
                                @endif
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
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection


