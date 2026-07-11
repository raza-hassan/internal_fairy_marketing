@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])

@section('content')

    @include('products.sidebar')

    <div class="ps-main__wrapper">

        @if (session('status'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>Success! </strong> {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif

        <header class="header--dashboard">
            <div class="header__left">
                <h3>Discount</h3>
            </div>
        </header>
        <section class="ps-items-listing">


            <div class="ps-section__content">
                <div class="table-responsive">
                    <table class="table ps-table ">
                        <thead>
                        <tr>
                            <th>#SR</th>
                            <th>Discount Amount Psqr/ft </th>
                            <th>Token Amount</th>

                            @if(Auth::user()->can('discount.prices.edit'))
                                <th class="hidden-phone">Actions</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody >
                        @foreach($discount as $key => $record)
                        <tr class="gradeX">
                            <td>
                                {{ $key+1 }}
                            </td>
                            <td>
                                {{ number_format($record->sqft_amount) }}
                            </td>
                            <td>
                                {{ number_format($record->token_amount) }}
                            </td>

                            @if(Auth::user()->can('discount.prices.edit'))
                                <td class="td-actions text-right ">
                                    <a rel="tooltip" class="fa fa-pencil mr-5 " title="Edit Data" href="{{ url('discount/edit', $record) }}" data-original-title="" ></a>
                                </td>
                            @endif

                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>


@endsection
