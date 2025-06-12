@can('stock_request_actions')
<div>
    @if($request->status === 'pending')
        <form action="{{ route('stock-requests.approve', $request->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success btn-sm" title="Approve"><i class="bi bi-check2-square"></i></i></button>
        </form>
        <form action="{{ route('stock-requests.reject', $request->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm" title="Reject"><i class="bi bi-ban"></i></button>
        </form>

        {{-- <button class="btn btn-primary btn-sm" onclick="editRequest({{ $request->id }})" title="Edit">
            <i class="bi bi-pencil-square"></i></a></button> --}}

        {{-- <button class="btn btn-danger btn-sm" onclick="deleteRequest({{ $request->id }})" title="Delete">
            <i class="bi bi-trash"></i></button> --}}
    @endif
</div>
@endcan