@extends('layouts.admin-app', ['activePage' => 'orders', 'titlePage' => __('Orders')])

@section('content')


<section role="main" class="content-body">
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
    <!-- start: page -->
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Orders</h2>
            
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead>
                    <tr>
                        <th>#Order</th>
                        <th>Name</th>
                        <th>Items</th>
                        <th>Subtotal</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th class="hidden-phone">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($orders as $record)
<?php
//echo "<pre>";
//print_r($record->user->name);
//exit;
?>
                    <tr class="gradeX">
                        <td>
                            {{ $record->id }}
                        </td>
                        <td>
                            <?php 
                            if(!empty($record->user) > 0){
                            echo $record->user->name;
                            }else{
                                echo "Empty";
                            }
                            ?>
                        </td>
                        <td>

                           {{ $record->items }}

                        </td>
                        <td>
                            {{ $record->subtotal }}
                        </td>
                        <td>
                            {{ $record->tax }}
                        </td>
                        <td>
                            {{ $record->total }}
                        </td>
                        <td>
                            @if($record->status == 0 )
                            Pending
                            @elseif($record->status == 1)
                            Processing
                            @elseif($record->status == 2)
                            Completed
                            @else
                            Canceled
                            @endif
                        </td>

                        <td>
                            {{date('d F Y', strtotime($record->created_at))}}
                        </td>
                        <td class="td-actions text-right">

                            <form action="{{ url('admin/order/destroy', $record) }}" method="post">
                                @csrf
                                @method('delete')

                                <a rel="tooltip" class="btn btn-success btn-link" href="{{ url('admin/order', $record) }}" data-original-title="" title="">
                                    <i class="material-icons">View Detail</i>
                                    <div class="ripple-container"></div>
                                </a>
                                <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">
                                    <i class="material-icons">Delete</i>
                                    <div class="ripple-container"></div>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </section>

    <!-- end: page -->
</section>

@endsection