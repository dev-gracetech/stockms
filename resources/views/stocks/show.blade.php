@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Stock Details - {{ $stock->name }}</h3>
            <p class="text-subtitle text-muted">View {{ $stock->name }} stock details here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card mt-4">
            <div class="card-header">Warehouse Quantities</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Warehouse</th>
                            <th>Quantity</th>
                            {{-- <th>Minimum Threshold</th> --}}
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stock->warehouses as $warehouse)
                        <tr>
                            <td>{{ $warehouse->name }}</td>
                            <td>{{ $warehouse->pivot->quantity }}</td>
                            {{-- <td>{{ $warehouse->pivot->minimum_threshold }}</td> --}}
                            <td>
                                @if($warehouse->pivot->quantity <= $warehouse->pivot->minimum_threshold)
                                    <span class="badge bg-warning">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                            <td>
                                @if($warehouse->id!=1)
                                <button class="btn btn-warning btn-sm replenish-stock" 
                                    data-stock-id="{{ $stock->id }}" 
                                    data-warehouse-id="{{ $warehouse->id }}"
                                    data-warehouse-quantity="{{ $stock->warehouses->find($warehouse->id)->pivot->quantity ?? 0 }}"
                                    data-bs-toggle="modal" data-bs-target="#replenishStockModal" title="Add Stock">
                                    <i class="bi bi-plus"></i>
                                </button>
                                @endif
                                <button class="btn btn-info btn-sm transfer-stock" data-stock-id="{{ $stock->id }}" 
                                    data-bs-toggle="modal" data-bs-target="#transferStockModal" title="Transfer Stock">
                                    <i class="bi bi-arrow-left-right"></i>
                                </button>
                                <button class="btn btn-warning btn-sm dispose-stock" data-stock-id="{{ $stock->id }}" 
                                    data-warehouse-id="{{ $warehouse->id }}"
                                    data-bs-toggle="modal" data-bs-target="#disposeStockModal" title="Dispose Stock">
                                    <i class="bi bi-trash2"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="table-info">
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $stock->total_quantity }}</strong></td>
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('stocks.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </section>
</div>

<!-- Replenish Stock Modal -->
<div class="modal fade" id="replenishStockModal" tabindex="-1" aria-labelledby="replenishStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replenishStockModalLabel">Add Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="replenishStockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="stock_id" id="stock_id">
                    <input type="hidden" name="warehouse_id" id="warehouse_id">
                    <input type="hidden" name="warehouse_quantity" id="warehouse_quantity">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="source" class="form-label">Source</label>
                        <input type="text" name="source" id="source" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dispose Stock Modal -->
<div class="modal fade" id="disposeStockModal" tabindex="-1" aria-labelledby="disposeStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disposeStockModalLabel">Dispose Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="disposeStockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="stock_id" id="dispose_stock_id">
                    <input type="hidden" name="warehouse_id" id="dispose_warehouse_id">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transfer Stock Modal -->
<div class="modal fade" id="transferStockModal" tabindex="-1" aria-labelledby="transferStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferStockModalLabel">Transfer Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="transferStockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="stock_id" id="transfer_stock_id">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="source" class="form-label">Source Warehouse</label>
                        <select name="source_id" id="source" class="form-control" required>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="destination" class="form-label">Destination Warehouse</label>
                        <select name="destination_id" id="destination" class="form-control" required>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            //Replenish stock
            // Get all "Replenish" buttons
            const replenishButtons = document.querySelectorAll('.replenish-stock');
            
            replenishButtons.forEach(button => {
                button.addEventListener('click', function () {
                stockId = button.getAttribute('data-stock-id');
                $('#stock_id').val(stockId);
                warehouseId = button.getAttribute('data-warehouse-id');
                $('#warehouse_id').val(warehouseId);
                $('#warehouse_quantity').val(button.getAttribute('data-warehouse-quantity'));
                $('#replenishStockModal').modal('show');
                });
            });
            // Handle the form submission
            document.getElementById('replenishStockForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch("{{ route('stocks.replenish', ['stock' => $stock->id]) }}", {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to replenish stock.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });

            //Dispose stock
            // Get all "Dispose" buttons
            const disposeButtons = document.querySelectorAll('.dispose-stock');
            disposeButtons.forEach(button => {
                button.addEventListener('click', function () {
                stockId = button.getAttribute('data-stock-id');
                $('#dispose_stock_id').val(stockId);
                $('#dispose_warehouse_id').val(button.getAttribute('data-warehouse-id'));
                $('#disposeStockModal').modal('show');
                });
            });
            // Handle the form submission
            document.getElementById('disposeStockForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch(`/stocks/${stockId}/dispose`, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to dispose stock.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });

            //Transfer stock
            // Get all "Transfer" buttons
            const transferButtons = document.querySelectorAll('.transfer-stock');
            transferButtons.forEach(button => {
                button.addEventListener('click', function () {
                stockId = button.getAttribute('data-stock-id');
                $('#transfer_stock_id').val(stockId);
                $('#transferStockModal').modal('show');
                });
            });
            // Handle the form submission
            document.getElementById('transferStockForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch(`/stocks/${stockId}/transfer`, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: data.message,
                            timer: 1500
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
@endsection