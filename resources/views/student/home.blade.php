@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f8f9fa;
    }

    .dashboard-card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .dashboard-header {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .welcome-text {
        font-size: 1.1rem;
        color: #555;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card dashboard-card">
                <div class="card-header bg-primary text-white dashboard-header">
                    <i class="fas fa-user-graduate me-2"></i>Student Dashboard
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success mb-3">
                        {{ session('status') }}
                    </div>
                    @endif

                    <p class="welcome-text">Welcome to your student dashboard! From here, you can:</p>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">📅 View and apply for activities</li>
                        <li class="list-group-item">✅ Track your approved activities</li>
                        <li class="list-group-item">📸 Scan QR codes for attendance</li>
                        <li class="list-group-item">📨 Receive updates and announcements</li>
                    </ul>

                    <div class="mt-4 text-end">
                        <a href="{{ route('activities.index') }}" class="btn btn-outline-primary">View Activities</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection