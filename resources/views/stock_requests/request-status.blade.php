@if($request->status === 'pending')
    <span class="badge bg-warning">Pending</span>
@elseif($request->status === 'approved')
    <span class="badge bg-success">Approved</span>
@else
    <span class="badge bg-danger">Rejected</span>
@endif