@extends('layouts.layout')

@section('content')
    <h1>Current Stock Report</h1>
    <form action="{{ route('reports.stock-track') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="product_name" class="form-label">Product</label>
                <select name="product_name" id="product_name" class="form-select">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product }}" {{ request('product_name') == $product ? 'selected' : '' }}>{{ $product }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary mt-4">Filter</button>
            </div>
        </div>
    </form>
    <table class="table datatable">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->stock->name}}</td>
                    <td>{{ $result->quantity_after }}</td>
                    <td>{{ $result->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection