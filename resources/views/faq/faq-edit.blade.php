@extends('layouts.admin')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endpush
@section('in_content')

    <div class="page-wrapper" style="background: #f2f7f8;">

        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Добавить faq</h3>
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
                        <form action="/faqadmin/{{ $faq->id }}" method="POST" class="form-horizontal form-material" >
                            {{ csrf_field() }}
                            @method("PUT")
                            <div class="form-group">
                                <label class="col-md-12">Вопрос</label>
                                <div class="col-md-12">
                                    <input type="text" value="{!! $faq->question !!}" name="question" class="form-control form-control-line">
                                    @if ($errors->has('question'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('question') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Ответ</label>
                                <div class="col-md-12">
                                    <textarea class="form-control form-control-lin"  name="answer" rows="6"  id="editor">{!! $faq->answer !!}</textarea>
                                    @if ($errors->has('answer'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('answer') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Для кого</label>
                                <div class="col-md-2">

                                    <select class="form-control" name="is_admin" >
                                        <option value="1" {{ ($faq->is_admin==1) ? "selected" : "" }} >Для админа</option>
                                        <option value="0" {{ ($faq->is_admin==0) ? "selected" : "" }}  >Для гостя</option>
                                    </select>
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
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection


@push('scripts')

    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=5y85yv1z81uofzj5d6j38pqqxzrxddxqym36d3n7kv5c5ejy"></script>

    <script>
        tinymce.init({
            selector: 'textarea',
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


