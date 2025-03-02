@extends('layouts.layout')

@section('content')
<div id="printable-area">
    <h1 id="report-title">Current Stock Report</h1>
    <div class="no-print">
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
                <button onclick="printReport()" class="btn btn-info mt-4">Print Report</button>
            </div>
        </div>
    </form>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Batch Number</th>
                <th>Buying Price</th>
                <th>Selling Price</th>
                <th>Quantity</th>
                <th>Date Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->stock->name}}</td>
                    <td>{{ $result->stock->batch}}</td>
                    <td>{{ $result->stock->price}}</td>
                    <td>{{ $result->stock->selling_price}}</td>
                    <td>{{ $result->quantity_after }}</td>
                    <td>{{ $result->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection