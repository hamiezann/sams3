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

    .btn-add-activity {
        background-color: var(--success-color);
        color: white;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 4px;
        border: none;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .btn-add-activity:hover {
        background-color: #1b5e20;
        color: white;
    }

    .btn-add-activity i {
        margin-right: 6px;
        font-size: 0.8rem;
    }

    .filter-card {
        background-color: white;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
    }

    .filter-card .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--secondary-color);
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 0.9rem;
        box-shadow: none;
    }

    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(26, 58, 95, 0.1);
    }

    .btn-filter {
        background-color: var(--primary-color);
        color: white;
        font-weight: 500;
        border: none;
        padding: 10px;
        transition: all 0.2s ease;
    }

    .btn-filter:hover {
        background-color: var(--secondary-color);
        color: white;
    }

    .activities-card {
        background-color: white;
        border-radius: 6px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: none;
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

    .table-striped>tbody>tr:nth-of-type(odd)>* {
        background-color: rgba(240, 244, 250, 0.4);
    }

    .table-bordered {
        border: 1px solid var(--border-color);
    }

    .badge {
        font-weight: 500;
        font-size: 0.8rem;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .badge-active {
        background-color: var(--success-color);
        color: white;
    }

    .badge-ended {
        background-color: var(--danger-color);
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .btn-view {
        background-color: var(--info-color);
        color: white;
        border: none;
        padding: 5px 10px;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 5px 10px;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .btn-delete {
        background-color: var(--danger-color);
        color: white;
        border: none;
        padding: 5px 10px;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .btn-view:hover {
        background-color: #01579b;
        color: white;
    }

    .btn-edit:hover {
        background-color: var(--secondary-color);
        color: white;
    }

    .btn-delete:hover {
        background-color: #b71c1c;
        color: white;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        color: #777;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #ccc;
    }

    @media (max-width: 768px) {
        .custom-table thead {
            display: none;
        }

        .custom-table,
        .custom-table tbody,
        .custom-table tr,
        .custom-table td {
            display: block;
            width: 100%;
        }

        .custom-table tr {
            margin-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
            background-color: #fff;
            padding: 1rem;
        }

        .custom-table td {
            text-align: right;
            padding-left: 50%;
            position: relative;
        }

        .custom-table td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-weight: bold;
            color: #6c757d;
            text-align: left;
        }
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="page-title">Activity Management</h5>
                <a href="{{ route('activities.create') }}" class="btn btn-add-activity">
                    <i class="fas fa-plus-circle"></i> Create New Activity
                </a>
            </div>

            {{-- Filter Form --}}
            <div class="filter-card mb-4">
                <div class="card-body">
                    <form action="{{ route('activities.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="title" class="form-label">Activity Title</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="title" id="title" value="{{ request('title') }}" class="form-control" placeholder="Search by title...">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="status" class="form-label">Activity Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Ended</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-filter w-100">
                                    <i class="fas fa-filter me-2"></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Activities Table --}}
            <div class="activities-card">
                <div class="card-body p-0">
                    @if($activity_list->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>No activities found matching your criteria.</p>
                        <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary mt-2">
                            <i class="fas fa-redo me-1"></i> Clear Filters
                        </a>
                    </div>
                    @else
                    <div class="table-responsive">
                        <!-- <table class="table table-bordered table-striped"> -->
                        <table class="table custom-table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="25%">Activity Title</th>
                                    <th width="15%">Location</th>
                                    <th width="15%">Start Date</th>
                                    <th width="15%">End Date</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activity_list as $index => $activity)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $activity->title }}</td>
                                    <td>{{ $activity->location }}</td>
                                    <td>{{ $activity->start_date }}</td>
                                    <td>{{ $activity->end_date }}</td>
                                    <td>
                                        @if($activity->is_ended)
                                        <span class="badge badge-ended">
                                            <i class="fas fa-times-circle me-1"></i> Ended
                                        </span>
                                        @else
                                        <span class="badge badge-active">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-view">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this activity? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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