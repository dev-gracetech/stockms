@extends('layouts.layout')

@section('content')
<div id="printable-area">
    <h1 id="report-title">Current Stock Report</h1>
    <div class="no-print">
    <form action="{{ route('reports.current-stocks') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="warehouse_id" class="form-label">Warehouse</label>
                <select name="warehouse_id" id="warehouse_id" class="form-select">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
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
                <th>Warehouse</th>
                <th>Batch Number</th>
                <th>Buying Price</th>
                <th>Selling Price</th>
                <th>Quantity</th>
                <th>Total Buying Price</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                     <td>{{ $result->stock }}</td>
                     <td>{{ $result->warehouse }}</td>
                    <td>{{ $result->batch }}</td>
                    <td>${{ $result->price }}</td>
                    <td>${{ $result->selling_price}}</td>
                    {{-- <td>{{ $result->s->name}}</td>
                    <td>{{ $result->s->batch}}</td>
                    <td>${{ $result->s->price}}</td>
                    <td>${{ $result->s->selling_price}}</td> --}}
                    <td>
                        {{ $result->total_quantity }}
                        {{-- @if(request('warehouse_id') == null)
                        {{ $result->total_quantity }}
                        @else
                        {{ $result->warehouse_qty(request('warehouse_id')) }}
                        @endif --}}
                    </td>
                    <td>${{ $result->total_quantity * $result->price }}</td>
                    <td>${{ $result->total_quantity * $result->selling_price }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td><strong>Grand Total:</strong></td>
                <td>{{ $totalQuantity }}</td>
                <td>${{ $totalBuyingPrice }}</td>
                <td>${{ $totalSales }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section("custom-scripts")
<script>
    $(document).ready(function() {
        $("#warehouse_id").select2();
        $("#product_name").select2();
    });
</script>
@endsection