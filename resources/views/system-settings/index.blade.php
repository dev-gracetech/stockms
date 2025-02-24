@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h1>Stock Management Settings</h1>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card row">
            <div class="d-flex align-items-start mt-4">
                <div class="nav flex-column nav-pills me-5" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" 
                        type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">General Settings</button>
                    <button class="nav-link" id="v-pills-warehouses-tab" data-bs-toggle="pill" data-bs-target="#v-pills-warehouses" 
                        type="button" role="tab" aria-controls="v-pills-warehouses" aria-selected="false">Warehouses</button>
                    <button class="nav-link" id="v-pills-branches-tab" data-bs-toggle="pill" data-bs-target="#v-pills-branches" 
                        type="button" role="tab" aria-controls="v-pills-branches" aria-selected="false">Branches</button>
                </div>
                <div class="tab-content col-6" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                        <h3>Base Settings</h3>
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('system-settings.update') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="high_stock_threshold">High Stock Threshold</label>
                                        <input type="number" name="high_stock_threshold" id="high_stock_threshold"
                                            class="form-control" value="{{ $settings->high_stock_threshold }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="low_stock_threshold">Low Stock Threshold</label>
                                        <input type="number" name="low_stock_threshold" id="low_stock_threshold"
                                            class="form-control" value="{{ $settings->low_stock_threshold }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="expiry_alert_days">Expiry Alert Days</label>
                                        <input type="number" name="expiry_alert_days" id="expiry_alert_days"
                                            class="form-control" value="{{ $settings->expiry_alert_days }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="default_stock_location">Default Stock Location</label>
                                        <input type="text" name="default_stock_location" id="default_stock_location"
                                            class="form-control" value="{{ $settings->default_stock_location }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="notification_email">Notification Email</label>
                                        <input type="email" name="notification_email" id="notification_email"
                                            class="form-control" value="{{ $settings->notification_email }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <input type="text" name="currency" id="currency"
                                            class="form-control" value="{{ $settings->currency }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Settings</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-warehouses" role="tabpanel" aria-labelledby="v-pills-warehouses-tab">
                        <h3>List Of Warehouses</h3>
                        <p class="text-subtitle text-muted">Manage your warehouses here.</p>
                        @include('system-settings.warehouse')
                    </div>
                    <div class="tab-pane fade" id="v-pills-branches" role="tabpanel" aria-labelledby="v-pills-branches-tab">
                        <h3>List Of Branches</h3>
                        <p class="text-subtitle text-muted">Manage your branches here.</p>
                        @include('system-settings.branch')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection