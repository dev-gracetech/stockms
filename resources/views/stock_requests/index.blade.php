@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Stock Request</h3>
            <p class="text-subtitle text-muted">Manage stock requests here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#addStockRequestModal">
                        Request Stock
                    </button>
                </div>
                <!-- Success or Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="card-body mt-3">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Stock</th>
                            <th>Branch</th>
                            <th>Quantity Requested</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockRequests as $request)
                            <tr>
                                <td>{{ $request->stock->name }}</td>
                                <td>{{ $request->branch->name }}</td>
                                <td>{{ $request->quantity_requested }}</td>
                                <td>
                                    @if($request->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($request->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->status === 'pending')
                                        @haspermission('stock_request_issue')
                                        <form action="{{ route('stock-requests.approve', $request->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Approve"><i class="bi bi-check2-square"></i></i></button>
                                        </form>
                                        <form action="{{ route('stock-requests.reject', $request->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" title="Reject"><i class="bi bi-ban"></i></button>
                                        </form>
                                        @endhaspermission
                                        {{-- <a href="{{ route('stock-requests.edit', $request->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i></a> --}}
                                        <button class="btn btn-primary btn-sm" onclick="editRequest({{ $request->id }})" title="Edit">
                                            <i class="bi bi-pencil-square"></i></a></button>

                                        <button class="btn btn-danger btn-sm" onclick="deleteRequest({{ $request->id }})" title="Delete">
                                            <i class="bi bi-trash"></i></button>
                                        {{-- <form action="{{ route('stock-requests.destroy', $request->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form> --}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Add Stock Request Modal -->
<div class="modal fade" id="addStockRequestModal" tabindex="-1" aria-labelledby="addStockRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockRequestModalLabel">Add Stock Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStockRequestForm">
                    @csrf
                    <div class="mb-3">
                        <label for="stock_id" class="form-label">Product</label>
                        <select class="form-select" id="stock_id" name="stock_id" required>
                            <option>Select Product</option>
                            @foreach($stocks as $stock)
                                <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity_requested" class="form-label">Quantity Requested</label>
                        <input type="number" class="form-control" id="quantity_requested" name="quantity_requested" required>
                    </div>
                    <div class="mb-3">
                        <label for="branch_id" class="form-label">Branch</label>
                        <select class="form-select" id="branch_id" name="branch_id" required>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
    $(document).ready(function () {

        // Create stock
        $('#addStockRequestForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('stock-requests.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#addStockRequestForm').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    alert('Error: ' + response.responseJSON.message);
                }
            });
        });
    });
</script>
@endsection