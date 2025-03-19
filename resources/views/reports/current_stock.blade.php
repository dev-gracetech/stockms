@extends('layouts.layout')

@section('content')
<div id="printable-area">
    <h1 id="report-title">Current Stock Report</h1>
    <div class="no-print">
    <form action="{{ route('reports.current-stocks') }}" method="GET" class="mb-3">
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
                <button type="submit" class="btn btn-primary mt-4">Filter</button>
                <button onclick="printReport()" class="btn btn-info mt-4">Print Report</button>
            </div>
        </div>
    </form>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Batch Number</th>
                <th>Buying Price</th>
                <th>Selling Price</th>
                <th>Quantity</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->name}}</td>
                    <td>{{ $result->batch}}</td>
                    <td>${{ $result->price}}</td>
                    <td>${{ $result->selling_price}}</td>
                    <td>{{ $result->quantity }}</td>
                    <td>${{ $result->quantity * $result->selling_price }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"></td>
                <td><strong>Grand Total:</strong></td>
                <td>{{ $totalQuantity }}</td>
                <td>${{ $totalSales }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection