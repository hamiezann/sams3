@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .activity-header {
        background: linear-gradient(90deg, #007bff 0%, #6610f2 100%);
        color: #fff;
        padding: 2rem;
        border-bottom: 4px solid #6610f2;
    }

    .activity-title {
        font-size: 2rem;
        font-weight: bold;
        margin-top: 0.5rem;
    }

    .activity-badge {
        background: #ffc107;
        color: #000;
        padding: 0.3rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .info-card:hover {
        background-color: #eef1f5;
    }

    .info-icon {
        font-size: 1.8rem;
        color: #007bff;
        margin-right: 1rem;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
    }

    .status-badge {
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-joined {
        background-color: #28a745;
        color: white;
    }

    .status-pending {
        background-color: #ffc107;
        color: #000;
    }

    .status-open {
        background-color: #17a2b8;
        color: white;
    }

    .activity-description,
    .qr-section,
    .additional-info {
        margin-top: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .description-content {
        line-height: 1.6;
    }

    .qr-instruction {
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .qr-code-wrapper {
        border: 1px dashed #ced4da;
        padding: 1rem;
        border-radius: 12px;
        background-color: #fff;
        display: inline-block;
    }

    .action-section {
        margin-top: 2.5rem;
        text-align: center;
    }

    .action-button {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
        border-radius: 50px;
    }

    .application-status-card {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        background: #f1f3f5;
        border-radius: 12px;
        padding: 1.5rem;
        gap: 1rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }

    .status-icon {
        width: 80px;
        height: 80px;
        font-size: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .status-icon:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
    }

    .status-joined {
        background: #28a745;
    }

    .status-pending {
        background: #ffc107;
        color: #000;
    }

    .status-details h5 {
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-item a {
        text-decoration: none;
    }
</style>
@endpush

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.activities') }}">Activities</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $activity->title }}</li>
        </ol>
    </nav>

    <div class="card shadow-lg border-0 overflow-hidden">
        <!-- Activity Header Banner -->
        <div class="activity-header">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <a href="{{ route('student.activities') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to Activities
                </a>

                <div class="activity-badge">{{ ucfirst($activity->type) }}</div>
            </div>
            <h1 class="activity-title">{{ $activity->title }}</h1>
        </div>

        <div class="card-body p-4">
            <!-- Key Info Section -->
            <div class="row activity-info-cards mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Location</span>
                            <span class="info-value">{{ $activity->location }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Dates</span>
                            <span class="info-value">
                                {{ \Carbon\Carbon::parse($activity->start_date)->format('d M Y') }}
                                @if($activity->start_date != $activity->end_date)
                                - {{ \Carbon\Carbon::parse($activity->end_date)->format('d M Y') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Status</span>
                            <span class="info-value status-badge {{ $application ? ($application->status === 'joined' ? 'status-joined' : 'status-pending') : 'status-open' }}">
                                {{ $application ? ucfirst($application->status) : 'Open' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="activity-description">
                <h4 class="section-title">
                    <i class="fas fa-info-circle me-2"></i>About This Activity
                </h4>
                <div class="description-content">
                    {!! nl2br(e($activity->description)) !!}
                </div>
            </div>

            <!-- QR Code Section (if applicable) -->
            @if ($application && $application->status === 'joined')
            <div class="qr-section">
                <h4 class="section-title">
                    <i class="fas fa-qrcode me-2"></i>Attendance
                </h4>

                @if ($activity->qr_code)
                <div class="text-center qr-container">
                    <p class="qr-instruction">Scan this QR code to mark your attendance</p>
                    <div class="qr-code-wrapper">
                        <img src="{{ asset('storage/' . $activity->qr_code) }}" alt="QR Code" class="img-fluid">
                    </div>
                </div>
                @else
                <div class="alert alert-info border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle fs-4 me-3"></i>
                        <div>
                            <strong>QR attendance code not yet available</strong>
                            <p class="mb-0">The organizer will provide the QR code closer to the event date.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Action Section -->
            <div class="action-section">
                @if (!$application)
                <form id="applyForm" method="POST" action="{{ route('student.activities.apply', $activity->id) }}">
                    @csrf
                    <button type="button" class="btn btn-primary btn-lg action-button" onclick="confirmApply()">
                        <i class="fas fa-plus-circle me-2"></i>Apply for This Activity
                    </button>
                </form>
                @else
                <div class="application-status-card">
                    <div class="status-icon {{ $application->status === 'joined' ? 'status-joined' : 'status-pending' }}">
                        <i class="fas {{ $application->status === 'joined' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                    </div>
                    <div class="status-details">
                        <h5>Application Status: {{ ucfirst($application->status) }}</h5>
                        <p>
                            @if($application->status === 'joined')
                            You've successfully joined this activity. Be sure to attend!
                            @else
                            Your application is being processed. Please check back later.
                            @endif
                        </p>
                        <form id="cancelForm" method="POST" action="{{ route('student.activities.cancel', $activity->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-outline-danger" onclick="confirmCancel()">
                                <i class="fas fa-times me-2"></i>Cancel Application
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <!-- Additional information could be added here -->
            @if($activity->additional_info)
            <div class="additional-info mt-4">
                <h4 class="section-title">
                    <i class="fas fa-clipboard-list me-2"></i>Additional Information
                </h4>
                <div class="alert alert-light border">
                    {!! nl2br(e($activity->additional_info)) !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#3085d6',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

<script>
    function confirmApply() {
        Swal.fire({
            title: 'Join This Activity?',
            text: "You're about to apply for \"{{ $activity->title }}\"",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check me-1"></i> Yes, Apply Now',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Not Now',
            confirmButtonColor: '#4285f4',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            focusConfirm: false,
            backdrop: `rgba(0,0,123,0.4)`
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Submitting Application...',
                    html: 'Please wait while we process your request',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Submit the form after a short delay to show loading animation
                setTimeout(() => {
                    document.getElementById('applyForm').submit();
                }, 800);
            }
        });
    }

    function confirmCancel() {
        Swal.fire({
            title: 'Cancel Your Application?',
            text: "You're about to withdraw from \"{{ $activity->title }}\"",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-times-circle me-1"></i> Yes, Cancel My Application',
            cancelButtonText: '<i class="fas fa-arrow-left me-1"></i> Keep My Application',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Processing...',
                    html: 'Please wait while we cancel your application',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Submit the form after a short delay to show loading animation
                setTimeout(() => {
                    document.getElementById('cancelForm').submit();
                }, 800);
            }
        });
    }
</script>
@endpush