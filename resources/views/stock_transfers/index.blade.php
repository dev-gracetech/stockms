@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Stock Transfers</h3>
            <p class="text-subtitle text-muted">Manage stock transfers here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary m-2">Request Stock Transfer</a>
                </div>
            </div>
            <div class="card-body mt-3">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Stock</th>
                            <th>From Branch</th>
                            <th>To Branch</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockTransfers as $stockTransfer)
                            <tr>
                                <td>{{ $stockTransfer->stock->name }}</td>
                                <td>{{ $stockTransfer->fromBranch->name }}</td>
                                <td>{{ $stockTransfer->toBranch->name }}</td>
                                <td>{{ $stockTransfer->quantity }}</td>
                                <td>
                                    @if($stockTransfer->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($stockTransfer->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($stockTransfer->status === 'pending')
                                        @haspermission('stock_transfer_approver')
                                        <form action="{{ route('stock-transfers.approve', $stockTransfer->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Approve"><i class="bi bi-check2-square"></i></i></button>
                                        </form>
                                        <form action="{{ route('stock-transfers.reject', $stockTransfer->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" title="Reject"><i class="bi bi-ban"></i></button>
                                        </form>
                                        @endhaspermission
                                        <a href="{{ route('stock-transfers.edit', $stockTransfer->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i></a>
                                        <form action="{{ route('stock-transfers.destroy', $stockTransfer->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection