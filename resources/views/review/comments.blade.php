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
                    <h3 class="text-themecolor m-b-0 m-t-0">Комментарии</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                    {{--<a class="btn pull-right hidden-sm-down btn-success text-white" href="/package/create"><i class="mdi mdi-plus-circle"></i> Create</a>--}}
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
                            <table class="table color-table success-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Отзыв</th>
                                    <th>Ответ на комментарий пользователя</th>
                                    <th>Пользователь</th>
                                    <th>Комментарий</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($comments as $key => $item)
                                    <td>{{ $item->id }}</td>
                                    <td><a href="{{ $item->review ? route('review_id', ['id' => $item->review->id]) : '' }}">{{ $item->review && $item->review->product ? $item->review->user->name . ' об ' . $item->review->product->title : $item->review->user->name }}</a></td>
                                    <td><a href="{{ $item->review ? route('review_id', ['id' => $item->review->id]) : '' }}">{{ $item->comment && $item->comment->user ? $item->comment->user->name : '' }}</a></td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->message }}</td>
                                    <td class="actions">
                                        <a href="{{ route('admin_comment_status', [ 'id' => $item->id, 'status' => 'delete' ]) }}" class="btn btn-xs btn-danger action delete" title="Удалить"><i class="mdi mdi-backspace"></i></a>
                                    </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $comments->links() }}
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


@push('styles')
    <style>
        .table td, .table th {
            padding: 10px 15px;
        }
        .action:not(:last-child) {
            margin-bottom: 10px;
        }
        .description {
            max-height: 100px;
            overflow: hidden;
            display: block;
            line-height: 1.4;
            position: relative;
            text-align: justify;
            overflow-wrap: anywhere;
        }
        .description .content {
            margin-bottom: 10px;
        }
        .description .more {
            position: absolute;
            right: 0;
            bottom: 1px;
            padding: 0 5px;
            display: block;
            line-height: 1.4;
            cursor: pointer;
            background-color: #dcdcdc;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deleteAlert(e) {
            if(!confirm("Вы уверены что хотите удалить?"))
                e.preventDefault();
        }
        jQuery(function ($) {
            $('.action.delete').click(deleteAlert);
            $('.description .more').click(function (e) {
                if($(this).parent().css('max-height') === '100%') {
                    $(this).text('показать больше...');
                    $(this).parent().css('max-height', '');
                } else {
                    $(this).text('скрыть содержимое...');
                    $(this).parent().css('max-height', '100%');
                }
            });
        });
    </script>

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

