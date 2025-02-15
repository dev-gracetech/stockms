@extends('layouts.layout')

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
            <div class="card-body mt-3">
                <div class="table-responsive datatable-minimal">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                            <tr>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection


                        {{-- <td>
                            @if($branch->stocks->isEmpty())
                                No stocks available.
                            @else
                                <ul>
                                    @foreach($branch->stocks as $stock)
                                        <li>
                                            {{ $stock->name }} - 
                                            Quantity: {{ $stock->pivot->quantity }}, 
                                            Expiry Date: {{ $stock->expiry_date }}, 
                                            Price: ${{ $stock->price }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td> --}}