@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h4 class="mb-0">My Activities</h4>

                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <select id="statusFilter" class="form-select form-select-sm" style="min-width: 150px;">
                        <option value="all">All Statuses</option>
                        <option value="joined">Joined</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    {{-- Sync to Google Calendar Button --}}
                    <form method="POST" action="{{ route('calendar.sync') }}" id="syncForm">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light text-primary d-flex align-items-center">
                            <i class="bi bi-calendar2-plus me-1"></i> Sync Calendar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle" id="activityTable">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                        <tr data-status="{{ strtolower($application->status) }}">
                            <td>{{ $application->activity->title }}</td>
                            <td>{{ ucfirst($application->activity->type) }}</td>
                            <td>{{ $application->activity->location }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($application->activity->start_date)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($application->activity->end_date)->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge 
                                    @if($application->status === 'joined') bg-success
                                    @elseif($application->status === 'pending') bg-warning text-dark
                                    @elseif($application->status === 'cancelled') bg-secondary
                                    @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('student.activities.view', $application->activity->id) }}"
                                    class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                    <i class="bi bi-eye me-1"></i> View
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