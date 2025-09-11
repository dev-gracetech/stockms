@extends('layouts.layout')

@section('content')
<div id="printable-area">
    <h1 id="report-title">Stock Status Report</h1>
    <div class="no-print">
    <form action="{{ route('reports.stock-details') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
                    <option value="overstock" {{ request('status') == 'overstock' ? 'selected' : '' }}>Overstock</option>
                    <option value="less_stock" {{ request('status') == 'less_stock' ? 'selected' : '' }}>Less Stock</option>
                    <option value="near_expiry" {{ request('status') == 'near_expiry' ? 'selected' : '' }}>Near Expiry</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
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
                @if(!Auth::user()->hasrole('branch user'))
                    <th>Location</th>
                @endif
                <th>Total Quantity</th>
                <th>Expiry Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <td>{{ $stock->name }} ({{$stock->batch}})</td>
                    @if(!Auth::user()->hasrole('branch user'))
                        <td>
                            @foreach($stock->branch as $branch)
                            <span class="badge bg-primary">{{ $branch->name}}</span>
                            @endforeach
                        </td>
                    @endif
                    <td>{{ $stock->quantity }}</td>
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
                        @elseif($stock->is_expired)
                            <span class="badge bg-danger">Expired</span>
                        @elseif($stock->no_expiry)
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
