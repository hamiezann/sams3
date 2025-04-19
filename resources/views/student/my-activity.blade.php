@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #e6f0fa 0%, #d4e9ff 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .activities-container {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .page-header {
        /* background: linear-gradient(90deg, #0d6efd, #4285f4); */
        background: linear-gradient(90deg, #007bff 0%, #6610f2 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
        margin-bottom: 20px;
    }

    .custom-table {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
    }

    .custom-table thead {
        background: linear-gradient(90deg, #0d6efd, #4285f4);
        color: white;
    }

    .custom-table th {
        font-weight: 500;
        border: none !important;
        padding: 12px 15px;
    }

    .custom-table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-color: #eaeaea;
    }

    .custom-table tbody tr {
        transition: all 0.3s ease;
    }

    .custom-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #f8f9ff;
    }

    .badge-activity {
        font-size: 0.8rem;
        padding: 5px 10px;
        border-radius: 12px;
    }

    .badge-workshop {
        background-color: #8e44ad;
        color: white;
    }

    .badge-seminar {
        background-color: #2ecc71;
        color: white;
    }

    .badge-competition {
        background-color: #e74c3c;
        color: white;
    }

    .badge-club {
        background-color: #f39c12;
        color: white;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        box-shadow: none;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #4285f4;
        box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.25);
    }

    .btn {
        border-radius: 8px;
        padding: 8px 16px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(90deg, #0d6efd, #4285f4);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #0b5ed7, #3367d6);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }

    .btn-success {
        background: linear-gradient(90deg, #198754, #28a745);
        border: none;
    }

    .btn-success:hover {
        background: linear-gradient(90deg, #157347, #208637);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(25, 135, 84, 0.3);
    }

    .btn-info {
        background: linear-gradient(90deg, #0dcaf0, #17a2b8);
        border: none;
        color: white;
    }

    .btn-info:hover {
        background: linear-gradient(90deg, #0bacce, #138496);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 202, 240, 0.3);
        color: white;
    }

    .date-badge {
        background-color: #e6f0fa;
        color: #0d6efd;
        font-weight: 500;
        border-radius: 6px;
        padding: 4px 8px;
        display: inline-block;
    }

    .location-badge {
        display: flex;
        align-items: center;
    }

    .location-badge i {
        color: #0d6efd;
        margin-right: 5px;
    }

    .btn-warning {
        background: linear-gradient(90deg, #ffc107, #fd7e14);
        border: none;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: #adb5bd;
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }

    .activity-count {
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 0.9rem;
        margin-left: 10px;
    }

    .breadcrumb-item a {
        text-decoration: none;
    }
</style>

<body>
    <div class="container py-5">

        <div class="activities-container fade-in">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>
                        My Activities
                        <!-- <span class="activity-count" id="activity-counter">0 activities</span> -->
                    </h3>
                </div>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    {{-- Sync to Google Calendar Button --}}
                    <form method="POST" action="{{ route('calendar.sync') }}" id="syncForm">
                        @csrf
                        <button type="submit" class="btn btn-light" id="refreshBtn">
                            <i class="bi bi-calendar2-plus me-1"></i> Sync Calendar
                        </button>
                    </form>
                </div>
            </div>

            <div class="container pb-4">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-filter text-primary"></i>
                            </span>
                            <select id="statusFilter" class="form-select" style="min-width: 200px;">
                                <option value="all">All Statuses</option>
                                <option value="joined">Joined</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table custom-table bg-white" id="activityTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-bookmark me-1"></i> Title</th>
                                <th class="d-none d-md-table-cell"><i class="fas fa-tag me-1"></i> Type</th>
                                <th class="d-none d-md-table-cell"><i class="fas fa-map me-1"></i> Location</th>
                                <th class="d-none d-md-table-cell"><i class="fas fa-calendar me-1"></i> Date</th>
                                <th class="d-none d-md-table-cell"><i class="fas fa-question-circle me-1"></i> Status</th>
                                <th><i class="fas fa-cog me-1"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                            <tr data-status="{{ strtolower($application->status) }}">
                                <td class="activity-title ">{{ $application->activity->title }}</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge-activity badge-{{ $application->activity->type }}">
                                        <i class="fas fa-{{ $application->activity->type == 'workshop' ? 'tools' : ($application->activity->type == 'seminar' ? 'microphone' : ($application->activity->type == 'competition' ? 'trophy' : 'users')) }} me-1"></i>
                                        {{ ucfirst($application->activity->type) }}
                                    </span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="location-badge">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $application->activity->location }}
                                    </span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @if ($application->activity->start_date == $application->activity->end_date)
                                    <span class="date-badge">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($application->activity->start_date)->format('d M Y') }}
                                    </span>
                                    @else
                                    <span class="date-badge">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($application->activity->start_date)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($application->activity->end_date)->format('d M Y') }}
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($application->status === 'joined')
                                    <span class="btn btn-success btn-sm disabled">
                                        <i class="fas fa-check-circle me-1"></i> Joined
                                    </span>
                                    @elseif ($application->status === 'pending')
                                    <span class="btn btn-warning btn-sm disabled">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('student.activities.view', $application->activity->id) }}"
                                        class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="View details">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($applications->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-emoji-frown fs-2"></i>
                        <p class="mt-2 mb-0">No activities found.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
@endsection

@push('scripts')
<script>
    document.getElementById('statusFilter').addEventListener('change', function() {
        let selectedStatus = this.value.toLowerCase();
        let rows = document.querySelectorAll('#activityTable tbody tr');

        rows.forEach(row => {
            if (selectedStatus === 'all') {
                row.style.display = '';
            } else {
                row.style.display = (row.getAttribute('data-status') === selectedStatus) ? '' : 'none';
            }
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('syncForm').addEventListener('submit', function(e) {
        // Show loading popup using SweetAlert2
        Swal.fire({
            title: 'Syncing...',
            html: 'Please wait while we sync your activities to Google Calendar.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Optional: disable the sync button
        document.getElementById('syncButton').disabled = true;
    });
</script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        confirmButtonColor: '#3085d6'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
        confirmButtonColor: '#d33'
    });
</script>
@endif



@endpush