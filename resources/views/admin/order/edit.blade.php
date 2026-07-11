@extends('layouts.admin-app', ['activePage' => 'orders', 'titlePage' => __('Edit Order')])

@section('content')
<section role="main" class="content-body">


    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Name : {{$order->user->name}}</h2>
                    <h2 class="panel-title">#{{$order->id}} Order Detail</h2>

                </header>
                <div class="panel-body">
                    <table class="table table-bordered table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr>
                                <th>#Item</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //print_r($order->ordermetas);
                            $discount = 0;
                            ?>
                            @foreach($order->ordermetas as $record)
                            <?php $discount = $discount + $record->discount; ?>
                            <tr>
                                <td>
                                    {{ $record->id }}
                                </td>
                                <td>

                                    {{ $record->sku }}

                                </td>
                                <td>

                                    {{ $record->title }}

                                </td>
                                <td>
                                    {{ $record->qty }}
                                </td>
                                <td>
                                    {{ number_format(($record->amount/$record->qty), 2) }}
                                </td>
                                <td>
                                    {{ $record->amount }}
                                </td>
                            </tr>
                            @endforeach

                            <tr style="text-align: right;">
                                <td colspan="4">
                                    SubTotal
                                </td>
                                <td colspan="2">
                                    {{ $order->subtotal }}
                                </td>
                            </tr>
                            <tr style="text-align: right;">
                                <td colspan="4">
                                    Tax
                                </td>
                                <td colspan="2">
                                    {{ $order->tax }}
                                </td>
                            </tr>
                            @if($discount > 0)
                            <tr style="text-align: right;">
                                <td colspan="4">
                                    Discount
                                </td>
                                <td colspan="2">
                                    {{ $discount }}
                                </td>
                            </tr>
                            @endif
                            <tr style="text-align: right;">
                                <td colspan="4">
                                    Total
                                </td>
                                <td colspan="2">
                                    {{ $order->total - $discount }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </section>
            <section class="panel">

                <div class="panel-body">

                    <form method="post" action="{{ url('admin/order/status', $order) }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label class="col-md-3 control-label">Order Status</label>
                            <div class="col-md-6">
                                <select class="form-control populate" name="status">
                                    <option value="0">Select Status</option>
                                    <option value="0" <?php
                                    if ($order->status == 0) {
                                        echo "selected";
                                    }
                                    ?>>Pending</option>
                                    <option value="1" <?php
                                    if ($order->status == 1) {
                                        echo "selected";
                                    }
                                    ?>>Processing</option>
                                    <option value="2" <?php
                                    if ($order->status == 2) {
                                        echo "selected";
                                    }
                                    ?>>Completed</option>
                                    <option value="3" <?php
                                    if ($order->status == 3) {
                                        echo "selected";
                                    }
                                    ?>>Canceled</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputPassword">&nbsp;</label>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </section>

        </div>
    </div>

    <!-- end: page -->
</section>
@endsection