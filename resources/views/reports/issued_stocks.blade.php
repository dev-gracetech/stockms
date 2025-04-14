@extends('layouts.layout')

@section('content')
<div id="printable-area">
    <h1 id="report-title">Issued Stocks to Branches</h1>
    <div class="no-print">
    <form action="{{ route('reports.issued-stocks') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="branch_id" class="form-label">Branch</label>
                <select name="branch_id" id="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
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
                <label for="start_date">Issue Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date">Issue End Date</label>
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
                <th>Issue Date</th>
                <th>Branch</th>
                <th>Product</th>
                <th>Batch Number</th>
                <th>Buying Price</th>
                <th>Selling Price</th>
                <th>Quantity</th>
                <th>Total Buying Price</th>
                <th>Total Sales</th>
                {{-- <th>Type</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($stockMovements as $movement)
                @if($movement->stock != null)
                <tr>
                    <td>{{ $movement->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $movement->toBranch->name }}</td>
                    <td>{{ $movement->stock->name }}</td>
                    <td>{{ $movement->stock->batch }}</td>
                    <td>${{ number_format($movement->stock->price, 2) }}</td>
                    <td>${{ number_format($movement->stock->selling_price, 2) }}</td>
                    <td>{{ $movement->quantity }}</td>
                    <td> ${{ number_format($movement->stock->price * $movement->quantity, 2) }} </td>
                    <td> ${{ number_format($movement->stock->selling_price * $movement->quantity, 2) }} </td>
                    {{-- <td>{{ ucfirst($movement->movement_type) }}</td> --}}
                </tr>
                @endif
            @endforeach
            <tr>
                <td colspan="6"></td>
                <td><strong>Grand Total:</strong></td>
                <td><strong>${{ number_format($totalBuyingPrice, 2) }}</strong></td>
                <td><strong>${{ number_format($totalSales, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section("custom-scripts")
<script>
    $(document).ready(function() {
        $("#branch_id").select2();
        $("#product_name").select2();
    });
</script>
@endsection