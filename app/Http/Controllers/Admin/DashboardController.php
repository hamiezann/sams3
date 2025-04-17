<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('type', 0)->count();
        $totalActivities = Activity::count();
        $completedActivities = Activity::where('is_ended', true)->count();
        $pendingRequests = Activity::whereHas('users', function ($query) {
            $query->where('status', 'pending');
        })->count();

        $upcomingActivities = Activity::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalActivities',
            'completedActivities',
            'pendingRequests',
            'upcomingActivities'
        ));
    }
}
