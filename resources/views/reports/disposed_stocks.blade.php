@extends('layouts.layout')

@section('content')
<div id="printable-area">
    <h1 id="report-title">Disposed Stocks</h1>
    <div class="no-print">
    <form action="{{ route('reports.disposed-stocks') }}" method="GET" class="mb-3">
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
                <label for="start_date">Disposed Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date">Disposed End Date</label>
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
                <th>Disposed Date</th>
                <th>Product</th>
                <th>Quantity Disposed</th>
                <th>Buying Price</th>
                <th>Total Buying Price</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $result->stock->name }}</td>
                    <td>{{ $result->quantity_disposed }}</td>
                    <td>${{ $result->stock->price }}</td>
                    <td>${{ $result->quantity_disposed * $result->stock->price }}</td>
                    <td>{{ $result->notes }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"></td>
                <td><strong>Grand Total:</strong></td>
                <td>${{ $totalBuyingPrice }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection