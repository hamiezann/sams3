@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #1a3a5f;
        --secondary-color: #102a43;
        --accent-color: #c8102e;
        --success-color: #2e7d32;
        --warning-color: #ed6c02;
        --danger-color: #c62828;
        --info-color: #0288d1;
        --light-gray: #f6f9fc;
        --medium-gray: #e6edf5;
        --border-color: #d0d6e2;
        --table-header-bg: #f0f4fa;
    }

    body {
        background-color: var(--light-gray);
        font-family: 'Roboto', sans-serif;
    }

    .page-title {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1.4rem;
        font-family: 'Merriweather', serif;
        border-left: 4px solid var(--accent-color);
        padding-left: 12px;
    }

    .students-card {
        border-radius: 6px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: none;
        background-color: white;
        overflow: hidden;
    }

    .card-header {
        background-color: var(--primary-color);
        color: white;
        font-weight: 500;
        padding: 15px 20px;
        border-bottom: none;
        display: flex;
        align-items: center;
    }

    .card-header i {
        margin-right: 10px;
        font-size: 1rem;
    }

    .table {
        margin-bottom: 0;
        font-size: 0.95rem;
    }

    .table th {
        background-color: var(--table-header-bg);
        color: var(--secondary-color);
        font-weight: 600;
        border-bottom-width: 1px;
        padding: 12px 15px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table td {
        padding: 12px 15px;
        vertical-align: middle;
        color: #444;
        border-color: var(--border-color);
    }

    .table-bordered {
        border: 1px solid var(--border-color);
    }

    .btn-view-activities {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 6px 12px;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        width: auto;
    }

    .btn-view-activities:hover {
        background-color: var(--secondary-color);
        color: white;
    }

    .btn-view-activities i {
        margin-right: 6px;
    }

    .empty-state {
        padding: 30px;
        text-align: center;
        color: #777;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 6px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
        border-bottom: none;
        padding: 15px 20px;
    }

    .modal-title {
        font-weight: 500;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
    }

    .modal-title i {
        margin-right: 8px;
    }

    .modal-body {
        padding: 20px;
    }

    .activity-item {
        background-color: var(--light-gray);
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid var(--primary-color);
    }

    .activity-title {
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .activity-detail {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .activity-detail i {
        width: 20px;
        margin-right: 8px;
        color: var(--primary-color);
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-left: 8px;
    }

    .status-approved {
        background-color: var(--success-color);
        color: white;
    }

    .status-pending {
        background-color: var(--warning-color);
        color: white;
    }

    .status-rejected {
        background-color: var(--danger-color);
        color: white;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 15px 20px;
    }

    .btn-close-modal {
        background-color: var(--secondary-color);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-close-modal:hover {
        background-color: #081c2f;
        color: white;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h5 class="page-title mb-4">Student Registry</h5>

            <div class="students-card">
                <div class="card-header">
                    <i class="fas fa-user-graduate"></i> Enrolled Students
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="8%" class="text-center">ID</th>
                                    <th width="37%">Student Name</th>
                                    <th width="35%">Email Address</th>
                                    <th width="20%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $index => $student)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-view-activities" data-bs-toggle="modal" data-bs-target="#activityModal{{ $student->id }}">
                                            <i class="fas fa-clipboard-list"></i> Activity Records
                                        </button>
                                    </td>
                                </tr>
                                @endforeach

                                @if ($students->isEmpty())
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <i class="fas fa-user-slash fa-2x mb-3 text-muted"></i>
                                        <p>No students are currently registered in the system.</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Template for Each Student --}}
@foreach ($students as $student)
<div class="modal fade" id="activityModal{{ $student->id }}" tabindex="-1" aria-labelledby="activityModalLabel{{ $student->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalLabel{{ $student->id }}">
                    <i class="fas fa-clipboard-check"></i> Activity Records - {{ $student->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (!$student->activityApplications->isEmpty())
                @foreach ($student->activityApplications as $activity)
                <div class="activity-item">
                    <div class="activity-title">{{ $activity->activity->title }}</div>
                    <div class="activity-detail">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ \Carbon\Carbon::parse($activity->start_date)->format('F j, Y') }}</span>
                    </div>
                    <div class="activity-detail">
                        <i class="fas fa-check-circle"></i>
                        <span>Status:</span>
                        <span class="status-badge 
                                {{ $activity->status == 'approved' ? 'status-approved' : '' }}
                                {{ $activity->status == 'pending' ? 'status-pending' : '' }}
                                {{ $activity->status == 'rejected' ? 'status-rejected' : '' }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
                @else
                <div class="text-center py-4">
                    <i class="fas fa-clipboard fa-3x mb-3 text-muted"></i>
                    <p>This student has not yet enrolled in any activities.</p>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endforeach
@endsection