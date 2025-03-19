@extends('layouts.layout')

@section('content')
<div id="printable-area">
    <h1 id="report-title">Branch Stock Report</h1>
    <div class="no-print">
    <form action="{{ route('reports.branch-stock') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="branch">Branch</label>
                <select name="branch" id="branch" class="form-control">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="start_date">Expiry Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date">Expiry End Date</label>
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
                <th>Branch Name</th>
                <th>Product</th>
                <th>Expiry Date</th>
                <th>Quantity</th>
                <th>Selling Price</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->branch->name}}</td>
                    <td>{{ $result->stock->name}} ({{ $result->stock->batch}})</td>
                    <td>{{ $result->stock->expiry_date }}</td>
                    <td>{{ $result->quantity }}</td>
                    <td>${{ $result->stock->selling_price }}</td>
                    <td>${{ $result->quantity * $result->stock->selling_price }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td><strong>Grand Total Sales:</strong></td>
                <td>${{ $totalSales }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection