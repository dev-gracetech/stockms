<div class="card">
    <div class="card-header">
        <div class="col-md-12 d-grid justify-content-md-end">
            <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" 
                data-bs-target="#createBranchModal">
                <i class="bi bi-plus-circle"></i> Add Branch
            </button>
        </div>
    </div>
    <div class="card-body mt-3">
        <div class="table-responsive">
            <table class="table datatable" id="branchesTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                        <tr>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->location }}</td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-primary edit-branch" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editBranchModal"
                                    data-id="{{ $branch->id }}"
                                    title="Edit">
                                    <i class="bi bi-pencil-square"></i>    
                                </button>
                                <!-- Delete Button -->
                                <button class="btn btn-danger btn-sm delete-branch" data-id="{{ $branch->id }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Branch Modal -->
<div class="modal fade" id="createBranchModal" tabindex="-1" aria-labelledby="createBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBranchModalLabel">Create Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createBranchForm" method="POST">
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
                    <button type="submit" class="btn btn-primary">Save</button>
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
                    <input type="hidden" id="edit_branch_id" name="id">
                    <div class="form-group">
                        <label for="edit_branch_name">Name</label>
                        <input type="text" name="name" id="edit_branch_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_branch_location">Location</label>
                        <input type="text" name="location" id="edit_branch_location" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBranchModal" tabindex="-1" aria-labelledby="deleteBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBranchModalLabel">Delete Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this branch?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@section('custom-scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Create Branch
        $('#createBranchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('branches.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#createBranchModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    alert('Error: ' + response.responseJSON.message);
                }
            });
        });

        // Edit Branch
        $('.edit-branch').on('click', function() {
            var branchId = $(this).data('id');
            $.ajax({
                url: `/branches/${branchId}/edit-data`,
                method: 'GET',
                success: function(response) {
                    $('#edit_branch_id').val(branchId);
                    $('#edit_branch_name').val(response.branch.name);
                    $('#edit_branch_location').val(response.branch.location);
                    $('#editBranchModal').modal('show');
                }
            });
        });

        $('#editBranchForm').on('submit', function(e) {
            e.preventDefault();
            var branchId = $('#edit_branch_id').val();
            $.ajax({
                url: "/branches/" + branchId,
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editBranchModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(response) {
                    alert('Error: ' + response.responseJSON.message);
                }
            });
        });

        // Delete Branch
        $('.delete-branch').on('click', function() {
            var branchId = $(this).data('id');
            $('#deleteBranchModal').modal('show');
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: "/branches/" + branchId,
                    method: 'DELETE',
                    success: function(response) {
                        $('#deleteBranchModal').modal('hide');
                        location.reload(); // Reload the page to reflect changes
                    },
                    error: function(response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
            });
        });

    });
</script>
@endsection