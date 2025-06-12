@extends('layouts.layout')

@section('custom-styles')
<style>
/* DataTable buttons spacing */
.dataTables_wrapper .dt-buttons {
    margin-bottom: 1rem;
}
</style>
@endsection

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
    <div class="col-md-12 d-grid justify-content-md-end">
        <!-- Success or Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    @can('stock_request')
                    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#addStockRequestModal">
                        <i class="bi bi-plus-circle"></i> Request Stock
                    </button>
                    @endcan
                </div>
            </div>
            <div class="card-body mt-3">
                <table class="table" id="table-stock-requests">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th> 
                            <th>Reference ID</th>
                            <th>Product</th>
                            <th>Batch</th>
                            {{-- <th>Branch</th> --}}
                            <th>Quantity Requested</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                            @can('stock_request_actions')
                            <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($stockRequests as $request)
                        @if($request->stock != null)
                            <tr>
                                 <td>
                                    <input type="checkbox" name="request_ids[]" value="{{ $request->id }}" 
                                        class="request-checkbox" {{ $request->status !== 'pending' ? 'disabled' : '' }}>
                                </td>
                                <td>{{ $request->reference_id }}</td>
                                <td>{{ $request->stock->name }}</td>
                                <td>{{ $request->stock->batch }}</td>
                                <td>{{ $request->branch->name }}</td>
                                <td>{{ $request->quantity_requested }}</td>
                                <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if($request->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($request->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                @can('stock_request_actions')
                                <td>
                                    @if($request->status === 'pending')
                                        <form action="{{ route('stock-requests.approve', $request->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Approve"><i class="bi bi-check2-square"></i></i></button>
                                        </form>
                                        <form action="{{ route('stock-requests.reject', $request->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" title="Reject"><i class="bi bi-ban"></i></button>
                                        </form>
                                    @endif
                                </td>
                                @endcan
                            </tr>
                        @endif
                        @endforeach --}}
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
                        <select class="form-select" id="stock_id" name="stock_id" required style="width: 100%">
                            <option>Select Product</option>
                            @foreach($stocks as $stock)
                                <option value="{{ $stock->id }}">{{ $stock->name }} ({{ $stock->batch }}) Available: {{ $stock->quantity}}; 
                                    Expiry: {{ $stock->expiry_date }}</option>
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
                            {{-- <option value="{{ $branch->id }}">{{ $branch->name }}</option> --}}
                            <option value="{{ $branch->id }}" {{ $user_branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Approve Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Approve Requests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkApproveForm" method="POST" action="{{ route('stock-requests.bulk-approve') }}">
                @csrf
                <div class="modal-body">
                    <p>You are about to approve <span id="selectedCount">0</span> requests.</p>
                    {{-- <div class="mb-3">
                        <label for="bulk_comments" class="form-label">Comments</label>
                        <textarea class="form-control" id="bulk_comments" name="comments"></textarea>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve All</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Reject Modal -->
<div class="modal fade" id="bulkRejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Reject Requests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkRejectForm" method="POST" action="{{ route('stock-requests.bulk-reject') }}">
                @csrf
                <div class="modal-body">
                    <p>You are about to reject <span id="selectedCountReject">0</span> requests.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject All</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
    $(document).ready(function () {

        $('#stock_id').select2({
            dropdownParent: $('#addStockRequestModal')
        });

        const canViewActions = {{ auth()->user()->can('stock_request_actions') ? 'true' : 'false' }};
        const columns = [
                { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                { data: 'id', name: 'id', visible: false },
                { data: 'reference_id', name: 'reference_id' },
                { data: 'stock.name', name: 'stock.name' },
                { data: 'stock.batch', name: 'stock.batch' },
                { data: 'quantity_requested', name: 'quantity_requested' },
                { data: 'created_at', name: 'created_at', render: function(data, type, row) { return moment(data).format('Y-MM-D'); } },
                { data: 'status', name: 'status' }
            ];

        const buttons = [];

        // Add actions column only if user has permission
        if (canViewActions) {
            columns.push({
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
            });

            buttons.push(
                {
                    text: '<i class="fas fa-check"></i> Bulk Approve',
                    className: 'btn btn-success bulk-approve-btn',
                    action: function() {
                        bulkAction('approve');
                    }
                },
                {
                    text: '<i class="fas fa-times"></i> Bulk Reject',
                    className: 'btn btn-danger bulk-reject-btn',
                    action: function() {
                        bulkAction('reject');
                    }
                }
            )
        }

        var table = $('#table-stock-requests').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('stock-requests.datatable') }}",
                type: "GET"
            },
            columns: columns,
            dom: 'Bfrtip',
            buttons: buttons
        });

        // Select/deselect all
        $('#select-all').on('click', function() {
            $('.request-checkbox').prop('checked', this.checked);
        });

        // $('#table-stock-requests').DataTable(
        //     {
        //         "order": [[ 0, "desc" ]]
        //     }
        // );
        // Create stock
        $('#addStockRequestForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('stock-requests.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#addStockRequestForm').modal('hide');
                    if(response.success)
                    {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                    }
                    else
                    {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    }
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    //alert('Error: ' + response.responseJSON.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.responseJSON.message,
                    });
                }
            });
        });

        // Bulk action function
        function bulkAction(action) {
            const selectedIds = [];
            $('.request-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
            
            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No requests selected',
                    text: 'Please select at least one request to perform this action.',
                })
                return;
            }
            
            if (action === 'approve') {
                $('#selectedCount').text(selectedIds.length);
                $('#bulkApproveForm input[name="request_ids"]').remove();
                
                selectedIds.forEach(function(id) {
                    $('#bulkApproveForm').append('<input type="hidden" name="request_ids[]" value="' + id + '">');
                });
                
                var bulkApproveModal = new bootstrap.Modal(document.getElementById('bulkApproveModal'));
                bulkApproveModal.show();
            } else {
                // Handle reject action
                $('#selectedCountReject').text(selectedIds.length);
                $('#bulkRejectForm input[name="request_ids"]').remove();
                
                selectedIds.forEach(function(id) {
                    $('#bulkRejectForm').append('<input type="hidden" name="request_ids[]" value="' + id + '">');
                });
                
                var bulkRejectModal = new bootstrap.Modal(document.getElementById('bulkRejectModal'));
                bulkRejectModal.show();
            }
        }

        // Bulk approval form submission
        $('#bulkApproveForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('stock-requests.bulk-approve') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#bulkApproveModal').modal('hide');
                    $('#table-stock-requests').DataTable().ajax.reload();
                    //showToast('success', response.message || 'Requests approved successfully');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    })
                },
                error: function(xhr) {
                    //showToast('error', xhr.responseJSON.message || 'Error approving requests');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON.message,
                    })
                }
            });
        });

        // Bulk rejection form submission
        $('#bulkRejectForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('stock-requests.bulk-reject') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#bulkRejectModal').modal('hide');
                    $('#table-stock-requests').DataTable().ajax.reload();
                    //showToast('success', response.message || 'Requests rejected successfully');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    })
                },
                error: function(xhr) {
                    //showToast('error', xhr.responseJSON.message || 'Error rejecting requests');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON.message,
                    })
                }
            });
        });
    });
</script>
@endsection