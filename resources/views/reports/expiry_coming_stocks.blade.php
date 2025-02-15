@extends('layouts.layout')

@section('content')
    <h1>Expiry Coming Stock Details</h1>
    <p>Showing stocks expiring within the next {{ $expiryAlertDays }} days.</p>
    <form action="{{ route('reports.expiry-coming-stocks') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="days_remaining">Days Remaining</label>
                <input type="number" name="days_remaining" id="days_remaining" class="form-control" value="{{ request('days_remaining', $expiryAlertDays) }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary mt-4">Filter</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Stock Name</th>
                <th>Quantity</th>
                <th>Expiry Date</th>
                <th>Days Remaining</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <td>{{ $stock->name }}</td>
                    <td>{{ $stock->quantity }}</td>
                    <td>{{ $stock->expiry_date }}</td>
                    <td>
                        {{ getRemainingDays($stock->expiry_date) }} days
                        @if(getRemainingDays($stock->expiry_date) <= 7)
                            <span class="badge bg-danger">Urgent</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection