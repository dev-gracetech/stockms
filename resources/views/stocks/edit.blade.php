@extends('layouts.layout')

@section('content')
<div class="page-title">
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <h3>Edit Stock: {{ $stock->name }}</h3>
                </div>
            </div>
            <div class="card-body mt-3">
                <form action="{{ route('stocks.update', $stock->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $stock->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="{{ $stock->quantity }}" required>
                    </div>
                    <div class="form-group">
                        <label for="expiry_date">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control" value="{{ $stock->expiry_date }}" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ $stock->price }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection