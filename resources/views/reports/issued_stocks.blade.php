@extends('layouts.layout')

@section('content')
    <h1>Issued Stocks to Branches</h1>
    <form action="{{ route('reports.issued-stocks') }}" method="GET" class="mb-3">
        <div class="row">
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
                <th>Stock Name</th>
                <th>Quantity</th>
                <th>From Branch</th>
                <th>To Branch</th>
                <th>Date Issued</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($issuedStocks as $transfer)
                <tr>
                    <td>{{ $transfer->stock->name }}</td>
                    <td>{{ $transfer->quantity }}</td>
                    <td>{{ $transfer->fromBranch->name }}</td>
                    <td>{{ $transfer->toBranch->name }}</td>
                    <td>{{ $transfer->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $transfer->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection