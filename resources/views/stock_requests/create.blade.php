@extends('layouts.layout')

@section('content')
<div class="page-title">
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <h3>Request Stock Transfer</h3>
                </div>
            </div>
            <div class="card-body mt-3">
                <form action="{{ route('stock-transfers.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="stock_id">Stock</label>
                        <select name="stock_id" class="form-control" required>
                            @foreach($stocks as $stock)
                                <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="from_branch_id">From Branch</label>
                        <select name="from_branch_id" class="form-control" required>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="to_branch_id">To Branch</label>
                        <select name="to_branch_id" class="form-control" required>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection