@extends('layouts.layout')
@section('custom-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <style>
        /* td.dt-control {
            cursor: pointer;
        }

        td.details-control::before {
            content: '+';
            color: blue;
            text-align: center;
            display: block;
        }

        tr.shown td.details-control::before {
            content: '-';
        } */

        /* td.dt-control {
        text-align: center;
        cursor: pointer;
        }
        
        td.dt-control:before {
            content: '►'; 
            color: #333;
        }
        
        tr.shown td.dt-control:before {
            content: '▼'; 
            color: #333;
        } */
    </style>
@endsection
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Branches</h3>
            <p class="text-subtitle text-muted">Manage your branches here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <a href="{{ route('branches.create') }}" class="btn btn-primary m-2"
                    data-bs-toggle="modal" data-bs-target="#createBranchModal">Add Branch</a>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive">
                    <table class="table" id="branches-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                                <tr>
                                    <td title="View Stocks"></td>
                                    <td>{{ $branch->name }}</td>
                                    <td>{{ $branch->location }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-branch" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editBranchModal"
                                            data-id="{{ $branch->id }}"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i>    
                                        </button>
                                        {{-- <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#editBranchModal{{ $branch->id }}" title="Edit"><i class="bi bi-pencil-square"></i></a> --}}
                                        <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" 
                                            data-bs-placement="top" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
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

<!-- Create Branch Modal -->
<div class="modal fade" id="createBranchModal" tabindex="-1" aria-labelledby="createBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBranchModalLabel">Create Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('branches.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Branch Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBranchModalLabel">Edit Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBranchForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_location">Location</label>
                        <input type="text" name="location" id="edit_location" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function() {
            var table = $('#branches-table').DataTable({
                columnDefs: [{
                    orderable: false,
                    className: 'dt-control',
                    targets: 0, // First column for expand/collapse
                }],
                order: [[1, 'asc']], // Sort by branch name
            });

            // Add event listener for opening and closing details
            $('#branches-table tbody').on('click', 'td.dt-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });

            // Format the child row data
            function format(d) {
                var branchName = d[1]; // Branch name is in the second column
                var branch = {!! $branches->toJson() !!}.find(b => b.name === branchName);

                if (!branch) return 'No data available';

                if (branch.stocks.length==0)
                {
                    html = '<p>No data available</p>';
                }else{
                    // Build the sub-table for stocks
                    var html = '<table class="table table-bordered">';
                    html += '<thead><tr><th>Stock Name</th><th>Quantity</th></tr></thead>';
                    html += '<tbody>';

                    branch.stocks.forEach(stock => {
                        html += '<tr>';
                        html += '<td>' + stock.name + '</td>';
                        html += '<td>' + stock.pivot.quantity + '</td>';
                        html += '</tr>';
                    });

                    html += '</tbody></table>';
                }
                return html;
            };

        });

    document.addEventListener('DOMContentLoaded', function () {
        // Get all "Edit" buttons
        const editButtons = document.querySelectorAll('.edit-branch');

        // Add click event listeners to each "Edit" button
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get branch data from the button's data attributes
                const branchId = button.getAttribute('data-id');
                //const name = button.getAttribute('data-name');
                //const location = button.getAttribute('data-location');

                $.ajax({
                    url: `/branches/${branchId}/edit`,
                    method: 'GET',
                    success: function(response) {
                        // Populate the edit modal with user data
                        $('#edit_name').val(response.name);
                        $('#edit_location').val(response.location);

                        // Update the form action URL
                        $('#editBranchForm').attr('action', `/branches/${branchId}`);
                    },
                    error: function(xhr) {
                        alert('An error occurred while fetching user data.');
                    }
                });
                
            });
        });
    });
    </script>
@endsection
