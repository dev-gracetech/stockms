@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Stock Movements</h3>
            <p class="text-subtitle text-muted">Manage stock movements here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#addStockMovementModal">
                        Add Stock Movement
                    </button>
                </div>
                <!-- Success or Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="card-body mt-3">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>From Warehouse</th>
                            <th>To Branch</th>
                            <th>Quantity</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $movement)
                            <tr>
                                <td>{{ $movement->id }}</td>
                                <td>{{ $movement->stock->name }}</td>
                                <td>{{ $movement->fromWarehouse ? $movement->fromWarehouse->name : 'N/A' }}</td>
                                <td>{{ $movement->toBranch ? $movement->toBranch->name : 'N/A' }}</td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ ucfirst($movement->movement_type) }}</td>
                                <td>{{ $movement->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection