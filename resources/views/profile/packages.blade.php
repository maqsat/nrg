@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <section class="product-table">
            <div class="container-fluid">

                <table>
                    <thead>
                    <tr>
                        <th scope="col">Пакет</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Линейка продукции</th>
                        <th scope="col">
                            Возможности дохода
                            (6-вида дохода)
                        </th>
                        <th scope="col">Купить</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Models\Package::where('status',1)->get() as $item)
                        @if($item->id > Auth::user()->package_id)
                            <tr class="tr-green">
                                <td data-label="Пакет">{{ $item->title }} <br> PV-{{ $item->pv - $user_package->pv }} <br> {{ $item->cost - $user_package->cost }}$</td>
                                <td data-label="Товары" class="td-img">{{  \App\Models\Status::find($item->rank)->title }}</td>
                                <td data-label="Линейка продукции" class="td-400"><p>{{ $item->goods }}</p></td>
                                <td data-label="Возможности дохода" class="td-200">
                                    <p>{{ $item->income }}</p>
                                </td>
                                <td data-label="Купить"><a class="btn-plain btn-buy">Upgrade</a></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                    {{--green green-dark pink blue red--}}
                </table>
            </div>
        </section>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
    @include('layouts.footer')
    <!-- ============================================================== -->
    </div>
@endsection

@push('styles')
    <style>
        .product-table table {
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .product-table table thead tr {
            background-color: #272525;
        }

        .product-table table thead tr td {
            color: white;
        }

        .product-table table .tr-green {
            background-color: #33a457;
        }

        .product-table table .tr-green-dark {
            background-color: #355c59;
        }

        .product-table table .tr-pink {
            background-color: #914883;
        }

        .product-table table .tr-blue {
            background-color: #2e4e7d;
        }

        .product-table table .tr-red {
            background-color: #721c1e;
        }

        .product-table table caption {
            font-size: 1.5em;
            margin: .5em 0 .75em;
        }

        .product-table table tr {
            border: 1px solid #ddd;
            padding: .35em;
        }

        .product-table table th,
        .product-table table td {
            padding: .625em;
            text-align: center;
            color: white;
            border-right: 1px solid white;
        }

        .product-table table th ul,
        .product-table table td ul {
            margin: 0 auto;
            list-style: decimal;
            text-align: left;
            max-width: 140px;
        }

        .product-table table th {
            font-size: .85em;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .product-table .td-img img {
            max-width: 280px;
            margin: 0 auto;
            -o-object-fit: contain;
            object-fit: contain;
        }

        .product-table .btn-buy {
            -webkit-border-radius: 3px;
            border-radius: 3px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            font-size: 14px;
            text-align: center;
            padding: 9px 15px;
            color: white;
            font-weight: 600;
            -webkit-transition: all 0.4s ease;
            -o-transition: all 0.4s ease;
            transition: all 0.4s ease;
        }

        .product-table .btn-buy:hover, .product-table .btn-buy:focus {
            background-color: white;
            color: black;
        }

        .product-table .td-200 {
            width: 200px;
        }

        .product-table .td-400 {
            width: 400px;
        }

        @media screen and (max-width: 968px) {
            .product-table table {
                border: 0;
            }
            .product-table table caption {
                font-size: 1.3em;
            }
            .product-table table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }
            .product-table table tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }
            .product-table table td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: right;
            }
            .product-table table td::before {
                /*
                  * aria-label has no advantage, it won't be read inside a table
                  content: attr(aria-label);
                  */
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }
            .product-table table td:last-child {
                border-bottom: 0;
            }
            .product-table .td-img img {
                max-width: 240px;
            }
            .product-table .td-200 {
                width: 100%;
            }
            .product-table table td {
                border-right: none;
                margin-bottom: 10px;
            }
            .product-table table td p {
                max-width: 150px;
                font-size: 12px;
                float: right;
            }
            .product-table table td p:after {
                content: '';
                display: table;
                clear: both;
            }
            .product-table table td ul {
                max-width: 150px;
                font-size: 12px;
                float: right;
            }
            .product-table table td ul:after {
                content: '';
                display: table;
                clear: both;
            }
            .product-table table td:after {
                content: '';
                display: table;
                clear: both;
            }
        }
    </style>
@endpush
@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection
