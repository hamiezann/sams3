@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0f8ff;
    }

    .btn-upload {
        background-color: #007bff;
        color: #fff;
    }

    .btn-upload:hover {
        background-color: #0056b3;
    }

    .btn-action {
        background-color: #ffc107;
        color: white;
    }

    .btn-action:hover {
        background-color: #e0a800;
        color: white;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .qr-code-img {
        max-width: 200px;
        height: auto;
    }

    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 140px;
        height: 48px;
        padding: 0 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        text-align: center;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
    }

    .action-button:focus {
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
        outline: none;
    }

    .action-button:active {
        transform: translateY(1px);
    }

    .warning-action {
        background-color: #fff;
        color: #d97706;
        border-color: #d97706;
    }

    .warning-action:hover {
        background-color: #d97706;
        color: #fff;
    }

    .neutral-action {
        background-color: #fff;
        color: #6b7280;
        border-color: #6b7280;
    }

    .neutral-action:hover {
        background-color: #6b7280;
        color: #fff;
    }

    .primary-action {
        background-color: #fff;
        color: #2563eb;
        border-color: #2563eb;
    }

    .primary-action:hover {
        background-color: #2563eb;
        color: #fff;
    }

    .danger-action {
        background-color: #fff;
        color: #dc2626;
        border-color: #dc2626;
    }

    .danger-action:hover {
        background-color: #dc2626;
        color: #fff;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Activity Detail: {{ $activity->title }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>Description:</strong> {{ $activity->description }}</p>
                    <p><strong>Type:</strong> {{ ucfirst($activity->type) }}</p>
                    <p><strong>Location:</strong> {{ $activity->location }}</p>
                    <p><strong>Start Date:</strong> {{ $activity->start_date }}</p>
                    <p><strong>End Date:</strong> {{ $activity->end_date }}</p>

                    <hr>

                    <h6>QR Code for Attendance</h6>


                    @if ($activity->qr_code)
                    <label class="form-label">QR Code:</label><br>
                    <img src="{{ asset('storage/' . $activity->qr_code) }}" alt="QR Code" class="img-thumbnail" width="200">
                    @else
                    <p class="text-muted">No QR code uploaded yet.</p>
                    @endif

                    <form action="{{ route('activities.uploadQr', $activity->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <div class="mb-2">
                            <input type="file" name="qr_code" id="qr_code" class="form-control" accept="image/*" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="qr_code" class="form-label">QR Code Image (Optional)</label>
                            <input type="file" name="qr_code" id="qr_code" class="form-control" accept="image/*">
                        </div> -->
                        <button type="submit" class="btn btn-upload btn-sm">Upload QR Code</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Requests --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Pending Join Requests</h6>
                </div>
                <div class="card-body p-0">
                    @if($pendingStudents->isEmpty())
                    <p class="text-muted p-3">No pending students.</p>
                    @else
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingStudents as $index => $app)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $app->user->name }}</td>
                                    <td>{{ $app->user->email }}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('admin.activity.accept', [$activity->id, $app->id]) }}" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success me-1">Accept</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.activity.reject', [$activity->id, $app->id]) }}" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Accepted Students --}}
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Accepted Students</h6>
                </div>
                <div class="card-body p-0">
                    @if($acceptedStudents->isEmpty())
                    <p class="text-muted p-3">No accepted students yet.</p>
                    @else
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($acceptedStudents as $index => $app)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $app->user->name }}</td>
                                    <td>{{ $app->user->email }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="d-flex justify-content-center align-items-center mt-4 flex-wrap gap-3">
        {{-- Left Section: End Activity --}}
        @if(!$activity->is_ended)
        <form action="{{ route('activities.end', $activity->id) }}" method="POST"
            onsubmit="return confirm('Mark this activity as ended?')" class="d-inline">
            @csrf
            <button type="submit" class="action-button warning-action">
                <i class="fas fa-flag-checkered me-2"></i>End Activity
            </button>
        </form>
        @endif

        {{-- Right Section: Back / Edit / Delete --}}
        <div class="d-flex gap-3 flex-wrap justify-content-end">
            <a href="{{ route('activities.index') }}" class="action-button neutral-action">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>

            <a href="{{ route('activities.edit', $activity->id) }}" class="action-button primary-action">
                <i class="fas fa-edit me-2"></i>Edit
            </a>

            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST"
                class="d-inline" onsubmit="return confirm('Are you sure you want to delete this activity? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button class="action-button danger-action">
                    <i class="fas fa-trash-alt me-2"></i>Delete
                </button>
            </form>
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