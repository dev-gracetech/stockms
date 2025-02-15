@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Stocks</h3>
            <p class="text-subtitle text-muted">Manage your stocks here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <a href="{{ route('stocks.create') }}" class="btn btn-primary">Add Stock</a>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Expiry Date</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                    <td>{{ $stock->expiry_date }}</td>
                                    <td>{{ $stock->price }}</td>
                                    <td>
                                        <a href="{{ route('stocks.edit', $stock->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" 
                                            data-bs-placement="top" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" 
                                            data-bs-placement="top" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this item?');"
                                            ><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
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