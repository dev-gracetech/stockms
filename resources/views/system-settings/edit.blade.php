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
</div>
@endsection