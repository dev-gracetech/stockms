@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Branch List Of Stocks</h3>
            <p class="text-subtitle text-muted">View branch stocks here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <!-- Success/Error Message -->
        <div id="responseMessage"></div>
        <div class="card">
            <div class="card-body mt-3">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Batch Number</th>
                                <th>Expiry Date</th>
                                <th>Buying Price</th>
                                <th>Selling Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->stock->name }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                    <td>{{ $stock->stock->batch }}</td>
                                    <td>{{ $stock->stock->expiry_date }}</td>
                                    <td>{{ $stock->stock->price }}</td>
                                    <td>{{ $stock->stock->selling_price }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                        data-bs-target="#dispenseFormModal" onclick="openDispenseModal({{ $stock->stock_id }})" title="Dispense">
                                            <i class="bi bi-cart4"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Dispense Modal -->
<div class="modal fade" id="dispenseFormModal" tabindex="-1" aria-labelledby="dispenseFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dispenseFormModalLabel">Dispense Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('branch-stock.dispense.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="stock_id" id="stock_id"> 
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="dispensed_to" class="form-label">Dispensed To</label>
                        <input type="text" name="dispensed_to" id="dispensed_to" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Dispense</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-scripts')
<script>
    function openDispenseModal(stockId) {
        document.getElementById('stock_id').value = stockId;
    }
</script>
@endsection