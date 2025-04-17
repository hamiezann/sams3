@extends('layouts.app')

@section('content')
@push('styles')
<style>
    body {
        background-color: #e6f0fa;
    }

    .custom-table thead {
        background-color: #0d6efd;
        color: white;
    }

    .custom-table tbody tr:hover {
        background-color: #d4e9ff;
    }
</style>

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

<div class="container py-4">
    <h3 class="mb-4 text-primary">Active Activities</h3>

    {{-- Filter & Search --}}
    <form id="filterForm" class="row gy-2 gx-3 align-items-center mb-4" onsubmit="return false;">
        <div class="col-12 col-md-6">
            <input type="text" id="activitySearch" name="search" class="form-control w-100" placeholder="Search activities...">
        </div>
        <div class="col-12 col-md-4">
            <div class="w-100">
                <select id="activityTypeFilter" name="type" class="form-select w-100">
                    <option value="">All Types</option>
                    @foreach(array_unique($activities->pluck('type')->toArray()) as $type)
                    <option value="{{ strtolower($type) }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 col-md-2">
            <button type="button" id="applyFilter" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>


    {{-- Activity Table --}}
    <div class="table-responsive">
        <table class="table table-bordered custom-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="activityTableBody">
                @foreach($activities as $activity)
                @php
                $status = $appliedActivities[$activity->id] ?? null;
                @endphp
                <tr>
                    <td class="activity-title">{{ $activity->title }}</td>
                    <td class="activity-type">{{ strtolower($activity->type) }}</td>
                    <td>
                        @if ($activity->start_date == $activity->end_date)
                        {{ \Carbon\Carbon::parse($activity->start_date)->format('d M Y') }}
                        @else
                        {{ \Carbon\Carbon::parse($activity->start_date)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($activity->end_date)->format('d M Y') }}
                        @endif
                    </td>
                    <td>{{ $activity->location }}</td>
                    <td>
                        <a href="{{ route('student.activities.view', $activity->id) }}" class="btn btn-info btn-sm">View</a>

                        @if ($status === 'joined')
                        <span class="btn btn-success btn-sm disabled">Joined</span>
                        @elseif ($status === 'pending')
                        <span class="btn btn-warning btn-sm disabled">Pending</span>
                        @else
                        <form method="POST" action="{{ route('student.activities.apply', $activity->id) }}" class="d-inline apply-form">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm apply-btn">Apply</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Filter functionality
    document.getElementById('applyFilter').addEventListener('click', function() {
        const searchValue = document.getElementById('activitySearch').value.toLowerCase();
        const typeValue = document.getElementById('activityTypeFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#activityTableBody tr');

        rows.forEach(row => {
            const title = row.querySelector('.activity-title').textContent.toLowerCase();
            const type = row.querySelector('.activity-type').textContent.toLowerCase();

            const matchSearch = title.includes(searchValue);
            const matchType = typeValue === '' || type === typeValue;

            row.style.display = (matchSearch && matchType) ? '' : 'none';
        });
    });

    // SweetAlert confirmation for Apply
    document.querySelectorAll('.apply-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const activityTitle = form.closest('tr').querySelector('.activity-title').textContent;

            Swal.fire({
                title: `Join "${activityTitle}"?`,
                text: "Are you sure you want to apply for this activity?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Apply'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Show success alert if session has 'success' message
</script>
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        confirmButtonColor: '#198754'
    });
</script>
@endif

@endpush

@endsection