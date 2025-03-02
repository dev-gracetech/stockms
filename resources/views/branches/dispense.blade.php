@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Dispense Stock</h1>
    <form action="{{ route('branch-stock.dispense') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="stock_id" class="form-label">Product</label>
            <select name="stock_id" id="stock_id" class="form-select" required>
                @foreach($stocks as $stock)
                    <option value="{{ $stock->id }}">{{ $stock->stock->name }}: {{ $stock->stock->batch }} ({{ $stock->quantity }} available)</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="dispensed_to" class="form-label">Dispensed To</label>
            <input type="text" name="dispensed_to" id="dispensed_to" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Dispense</button>
    </form>
</div>
@endsection