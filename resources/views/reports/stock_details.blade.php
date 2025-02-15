@extends('layouts.layout')

@section('content')
    <h1>Stock Details Report</h1>
    <form action="{{ route('reports.stock-details') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
                    <option value="overstock" {{ request('status') == 'overstock' ? 'selected' : '' }}>Overstock</option>
                    <option value="less_stock" {{ request('status') == 'less_stock' ? 'selected' : '' }}>Less Stock</option>
                    <option value="near_expiry" {{ request('status') == 'near_expiry' ? 'selected' : '' }}>Near Expiry</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary mt-4">Filter</button>
            </div>
        </div>
    </form>
    <table class="table datatable">
        <thead>
            <tr>
                <th>Stock Name</th>
                <th>Total Quantity</th>
                <th>Expiry Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <td>{{ $stock->name }}</td>
                    <td>{{ $stock->total_quantity }}</td>
                    <td>{{ $stock->expiry_date }}</td>
                    <td>
                        @if($stock->is_overstock)
                            <span class="badge bg-warning">Overstock</span>
                        @elseif($stock->is_less_stock)
                            <span class="badge bg-danger">Less Stock</span>
                        @else
                            <span class="badge bg-success">Normal</span>
                        @endif

                        @if($stock->is_near_expiry)
                            <span class="badge bg-danger">Near Expiry</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection