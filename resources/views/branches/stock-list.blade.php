@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Stocks</h3>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <!-- Success/Error Message -->
        <div id="responseMessage"></div>
        <div class="card bg-light">
            <div class="card-body">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Batch Number</th>
                                <th>Expiry Date</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockMovements as $stockMovement)
                                <tr>
                                    <td>{{ $stockMovement['stock']->name }}</td>
                                    <td>{{ $stockMovement['total_quantity'] }}</td>
                                    <td>{{ $stockMovement['stock']->batch }}</td>
                                    <td>{{ $stockMovement['stock']->expiry_date }}</td>
                                    <td>{{ $stockMovement['stock']->price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection