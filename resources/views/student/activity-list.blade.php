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
</style>

<body>
    <div class="container py-5">
        <div class="activities-container fade-in">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Student Activities
                        <span class="activity-count" id="activity-counter">0 activities</span>
                    </h3>
                </div>
                <button class="btn btn-light" id="refreshBtn">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            </div>

            <div class="container pb-4">
                <!-- Filter & Search -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <form id="filterForm" class="row g-3" onsubmit="return false;">
                            <div class="col-12 col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input type="text" id="activitySearch" name="search" class="form-control" placeholder="Search activities by name, location...">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-filter text-primary"></i>
                                    </span>
                                    <select id="activityTypeFilter" name="type" class="form-select">
                                        <option value="">All Activity Types</option>
                                        @foreach(array_unique($activities->pluck('type')->toArray()) as $type)
                                        <option value="{{ strtolower($type) }}">{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <button type="button" id="applyFilter" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Activity Table -->
                <div class="table-responsive">
                    <table class="table custom-table bg-white">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-bookmark me-1"></i> Title</th>
                                <th class="d-none d-md-table-cell"><i class="fas fa-tag me-1"></i> Type</th>
                                <th class="d-none d-md-table-cell"><i class="fas fa-calendar me-1"></i> Date</th>
                                <th class="d-none d-md-table-cell><i class=" fas fa-map-marker-alt me-1"></i> Location</th>
                                <th><i class="fas fa-cog me-1"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody id="activityTableBody">
                            @foreach($activities as $activity)
                            @php
                            $status = $appliedActivities[$activity->id] ?? null;
                            $type = strtolower($activity->type);
                            @endphp
                            <tr class="activity-row" data-type="{{ $type }}">
                                <td class="activity-title ">{{ $activity->title }}</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge-activity badge-{{ $type }}">
                                        <i class="fas fa-{{ $type == 'workshop' ? 'tools' : ($type == 'seminar' ? 'microphone' : ($type == 'competition' ? 'trophy' : 'users')) }} me-1"></i>
                                        {{ ucfirst($type) }}
                                    </span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @if ($activity->start_date == $activity->end_date)
                                    <span class="date-badge">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($activity->start_date)->format('d M Y') }}
                                    </span>
                                    @else
                                    <span class="date-badge">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($activity->start_date)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($activity->end_date)->format('d M Y') }}
                                    </span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="location-badge">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $activity->location }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('student.activities.view', $activity->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="View details">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>

                                        @if ($status === 'joined')
                                        <span class="btn btn-success btn-sm disabled">
                                            <i class="fas fa-check-circle me-1"></i> Joined
                                        </span>
                                        @elseif ($status === 'pending')
                                        <span class="btn btn-warning btn-sm disabled">
                                            <i class="fas fa-clock me-1"></i> Pending
                                        </span>
                                        @else
                                        <form method="POST" action="{{ route('student.activities.apply', $activity->id) }}" class="d-inline apply-form">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm apply-btn">
                                                <i class="fas fa-plus-circle me-1"></i> Apply
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div id="emptyState" class="empty-state d-none">
                        <i class="fas fa-search"></i>
                        <h4>No Activities Found</h4>
                        <p>Try changing your search criteria or check back later for new activities.</p>
                        <button id="resetFilters" class="btn btn-outline-primary mt-2">Reset Filters</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Update activity counter
            function updateActivityCounter() {
                const visibleRows = $('#activityTableBody tr:visible').length;
                $('#activity-counter').text(visibleRows + (visibleRows === 1 ? ' activity' : ' activities'));
            }

            updateActivityCounter();

            // Handle filter
            $('#applyFilter').click(function() {
                filterActivities();
            });

            // Handle search on typing
            $('#activitySearch').on('keyup', function() {
                filterActivities();
            });

            // Also filter when dropdown changes
            $('#activityTypeFilter').change(function() {
                filterActivities();
            });

            // Reset filters
            $('#resetFilters').click(function() {
                $('#activitySearch').val('');
                $('#activityTypeFilter').val('');
                filterActivities();
            });

            function filterActivities() {
                const searchText = $('#activitySearch').val().toLowerCase();
                const selectedType = $('#activityTypeFilter').val().toLowerCase();
                let visibleCount = 0;

                $('#activityTableBody tr').each(function() {
                    const title = $(this).find('.activity-title').text().toLowerCase();
                    const location = $(this).find('.location-badge').text().toLowerCase();
                    const type = $(this).data('type').toLowerCase();

                    const matchesSearch = title.includes(searchText) || location.includes(searchText);
                    const matchesType = selectedType === '' || type === selectedType;

                    if (matchesSearch && matchesType) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                // Toggle empty state
                if (visibleCount === 0) {
                    $('#emptyState').removeClass('d-none');
                    $('.table').addClass('d-none');
                } else {
                    $('#emptyState').addClass('d-none');
                    $('.table').removeClass('d-none');
                }

                updateActivityCounter();
            }

            // Refresh button animation
            $('#refreshBtn').click(function() {
                const $this = $(this);
                $this.find('i').addClass('fa-spin');

                setTimeout(function() {
                    $this.find('i').removeClass('fa-spin');
                    Swal.fire({
                        icon: 'success',
                        title: 'Refreshed!',
                        text: 'Activity list has been updated',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }, 800);
            });

            // Apply form submission with confirmation
            $('.apply-form').submit(function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Apply for this activity?',
                    text: "You're about to submit your application for this activity",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, apply now!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        const button = $(form).find('.apply-btn');
                        const originalText = button.html();
                        button.html('<i class="fas fa-spinner fa-spin"></i> Applying...');
                        button.prop('disabled', true);

                        setTimeout(() => {
                            form.submit();
                        }, 800);
                    }
                });
            });
        });
    </script>
</body>

@endsection