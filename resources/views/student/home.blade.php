@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Nunito', sans-serif;
    }

    .dashboard-container {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .dashboard-card {
        border-radius: 15px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    .dashboard-header {
        font-size: 1.5rem;
        font-weight: 700;
        padding: 1.25rem 1.5rem;
        /* background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); */
        background: linear-gradient(90deg, #007bff 0%, #6610f2 100%);
    }

    .welcome-text {
        font-size: 1.2rem;
        color: #374151;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .feature-list {
        margin-bottom: 2rem;
    }

    .feature-item {
        padding: 1rem 1.25rem;
        border-left: none;
        border-right: none;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        transition: background-color 0.2s;
    }

    .feature-item:hover {
        background-color: #f3f4f6;
    }

    .feature-icon {
        font-size: 1.5rem;
        margin-right: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: #e0e7ff;
        border-radius: 50%;
        color: #4f46e5;
    }

    .feature-text {
        font-weight: 500;
        color: #1f2937;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        grid-gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #4f46e5;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-university {
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-primary-university {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }

    .btn-primary-university:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }

    .btn-outline-university {
        border: 2px solid #4f46e5;
        color: #4f46e5;
        background-color: transparent;
    }

    .btn-outline-university:hover {
        background-color: #4f46e5;
        color: white;
    }

    .upcoming-section {
        margin-top: 1.5rem;
    }

    .section-heading {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .section-heading i {
        margin-right: 0.5rem;
    }

    .event-card {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background-color: #ffffff;
        border-radius: 10px;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .event-date {
        width: 60px;
        height: 60px;
        background-color: #eef2ff;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-right: 1rem;
    }

    .event-month {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #4f46e5;
    }

    .event-day {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }

    .event-details {
        flex-grow: 1;
    }

    .event-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #1f2937;
    }

    .event-info {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .notifications-badge {
        position: relative;
        padding: 4px 8px;
        background-color: #ef4444;
        color: white;
        border-radius: 50%;
        font-size: 0.75rem;
        margin-left: 0.5rem;
    }
</style>

<div class="container dashboard-container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card dashboard-card">
                <div class="card-header text-white dashboard-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-user-graduate me-2"></i>Student Dashboard
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="d-none d-md-block">
                            Last login:
                            @if ($last_login)
                            {{ \Carbon\Carbon::parse($last_login)->isToday() ? 'Today, ' . \Carbon\Carbon::parse($last_login)->format('h:i A') : \Carbon\Carbon::parse($last_login)->isoFormat('dddd, MMMM Do YYYY, h:mm A') }}
                            <!-- {{ \Carbon\Carbon::parse($last_login)->timezone('Asia/Kuala_Lumpur')->format('h:i A') }} -->

                            @else
                            First time login
                            @endif
                        </div>

                        <div class="ms-3 position-relative">
                            <i class="fas fa-bell"></i>
                            <span class="notifications-badge">3</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                    </div>
                    @endif

                    <h2 class="welcome-text">Welcome back, <strong>{{ Auth::user()->name }}</strong>! Your academic journey continues here.</h2>

                    <!-- Quick Stats Section -->
                    <div class="quick-stats">
                        <div class="stat-card">
                            <div class="stat-number">{{ $upcomingActivities->count()}}</div>
                            <div class="stat-label">Upcoming Activities</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">12</div>
                            <div class="stat-label">Credits Earned</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">3</div>
                            <div class="stat-label">New Announcements</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">85%</div>
                            <div class="stat-label">Attendance Rate</div>
                        </div>
                    </div>

                    <div class="section-heading">
                        <i class="fas fa-compass me-2"></i>Explore Features
                    </div>

                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="feature-text">Browse and register for campus activities and events</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="feature-text">Monitor your activity participation and credits</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div class="feature-text">Quick QR code scanning for event check-ins</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="feature-text">Stay updated with important university announcements</div>
                        </div>
                    </div>

                    <!-- Upcoming Events Section -->
                    <div class="upcoming-section">
                        <div class="section-heading">
                            <i class="fas fa-calendar-week me-2"></i>Upcoming Events
                        </div>
                        @foreach($upcomingActivities as $activity)
                        <div class="event-card">
                            <div class="event-date">
                                <div class="event-month">{{ \Carbon\Carbon::parse($activity->start_date)->format('M') }}</div>
                                <div class="event-day">{{ \Carbon\Carbon::parse($activity->start_date)->format('d') }}</div>
                            </div>
                            <div class="event-details">
                                <div class="event-title">{{ $activity->title }}</div>
                                <div class="event-info">
                                    {{ $activity->location }}<br>
                                    {{ \Carbon\Carbon::parse($activity->start_date)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($activity->end_date)->format('d M Y') }}
                                </div>
                            </div>
                            <a href="{{ route('student.activities.view', $activity->id) }}" class="btn btn-sm btn-outline-university">Register</a>
                        </div>
                        @endforeach

                        <!-- <div class="event-card">
                            <div class="event-date">
                                <div class="event-month">Apr</div>
                                <div class="event-day">25</div>
                            </div>
                            <div class="event-details">
                                <div class="event-title">Research Symposium</div>
                                <div class="event-info">Science Building, 1:00 PM - 5:00 PM</div>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-university">Register</a>
                        </div> -->
                    </div>

                    <div class="action-buttons mt-4">
                        <!-- <a href="{{ route('home') }}" class="btn btn-outline-university">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a> -->
                        <a href="{{ route('student.activities') }}" class="btn btn-primary-university">
                            <i class="fas fa-compass me-2"></i>Explore Activities
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection