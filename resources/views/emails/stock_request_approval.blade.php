<!DOCTYPE html>
<html>
<head>
    <title>Stock Request Approval</title>
</head>
<body>
    <h2>Stock Request Approval Required</h2>
    <p>A new stock request from {{ $stockRequest->branch->name }} has been submitted and requires your approval.</p>
    <p><strong>Request ID:</strong> {{ $stockRequest->reference_id }}</p>
    <p><strong>Stock:</strong> {{ $stockRequest->stock->name }}</p>
    <p><strong>Quantity Requested:</strong> {{ $stockRequest->quantity_requested }}</p>
    {{-- <p><strong>Notes:</strong> {{ $stockRequest->notes }}</p> --}}
    {{-- <p>
        <a href="{{ route('stock-requests.approve', $stockRequest->id) }}" style="background-color: green; color: white; padding: 10px 20px; text-decoration: none;">Approve</a>
        <a href="{{ route('stock-requests.reject', $stockRequest->id) }}" style="background-color: red; color: white; padding: 10px 20px; text-decoration: none;">Reject</a>
    </p> --}}
</body>
</html>