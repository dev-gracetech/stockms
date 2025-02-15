@extends('layouts.layout')

@section('content')
    <h1>Branch Stock Report</h1>
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
                <button type="submit" class="btn btn-primary mt-4">Filter</button>
            </div>
        </div>
    </form>
    <table class="table datatable">
        <thead>
            <tr>
                <th>Branch Name</th>
                <th>Stock Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $branch)
                @foreach($branch->stocks as $stock)
                    <tr>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $stock->name }}</td>
                        <td>{{ $stock->pivot->quantity }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection