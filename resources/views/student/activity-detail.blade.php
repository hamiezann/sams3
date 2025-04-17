@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <a href="{{ route('student.activities') }}" class="btn btn-secondary mb-3">← Back to Activity List</a>

            <h2 class="card-title text-primary">{{ $activity->title }}</h2>
            <p class="text-muted mb-1"><strong>Type:</strong> {{ ucfirst($activity->type) }}</p>
            <p class="text-muted mb-1"><strong>Location:</strong> {{ $activity->location }}</p>
            <p class="text-muted mb-1">
                <strong>Date:</strong>
                {{ \Carbon\Carbon::parse($activity->start_date)->format('d M Y') }}
                -
                {{ \Carbon\Carbon::parse($activity->end_date)->format('d M Y') }}
            </p>
            <hr>
            <p>{!! nl2br(e($activity->description)) !!}</p>

            {{-- QR section --}}
            @if ($application && $application->status === 'joined')
            <div class="mt-4 text-center">
                @if ($activity->qr_code)
                <h5 class="text-success">Scan this QR code to mark attendance</h5>
                <img src="{{ asset('storage/' . $activity->qr_code) }}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                @else
                <div class="alert alert-warning mt-3" role="alert">
                    QR Attendance is not yet available for this activity.
                </div>
                @endif
            </div>
            @endif

            {{-- Application buttons --}}
            <div class="mt-4 text-center">
                @if (!$application)
                <form id="applyForm" method="POST" action="{{ route('student.activities.apply', $activity->id) }}">
                    @csrf
                    <button type="button" class="btn btn-success" onclick="confirmApply()">Apply for Activity</button>
                </form>
                @else
                <form id="cancelForm" method="POST" action="{{ route('student.activities.cancel', $activity->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger" onclick="confirmCancel()">Cancel Application</button>
                </form>
                <p class="mt-2 text-muted">Status: {{ ucfirst($application->status) }}</p>
                @endif
            </div>
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

<script>
    function confirmApply() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to apply for this activity.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Apply',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('applyForm').submit();
            }
        });
    }

    function confirmCancel() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to cancel your application.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Cancel',
            cancelButtonText: 'Go Back',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cancelForm').submit();
            }
        });
    }
</script>
@endpush