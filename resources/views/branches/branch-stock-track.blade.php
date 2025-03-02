@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Branch Dispensed Stock</h3>
            <p class="text-subtitle text-muted">View dispensed stock here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
    <!-- Success/Error Message -->
        <div id="responseMessage"></div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Dispensed To</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $movement->stock->name }}</td>
                                    <td>{{ $movement->quantity }}</td>
                                    <td>{{ $movement->dispensed_to }}</td>
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