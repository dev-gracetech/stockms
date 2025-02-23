@extends('layouts.layout')

@section('content')
    <h1>Edit Stock Transfer</h1>
    <form action="{{ route('stock-transfers.update', $stockTransfer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="stock_id">Stock</label>
            <select name="stock_id" class="form-control" required>
                @foreach($stocks as $stock)
                    <option value="{{ $stock->id }}" {{ $stockTransfer->stock_id == $stock->id ? 'selected' : '' }}>{{ $stock->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="from_branch_id">From Branch</label>
            <select name="from_branch_id" class="form-control" required>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $stockTransfer->from_branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="to_branch_id">To Branch</label>
            <select name="to_branch_id" class="form-control" required>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $stockTransfer->to_branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ $stockTransfer->quantity }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $stockTransfer->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $stockTransfer->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $stockTransfer->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection