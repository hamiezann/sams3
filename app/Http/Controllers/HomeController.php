<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Activity;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get total number of activities (0 if none)
        $totalActivities = Activity::count();

        // Get last login time of current user
        $last_login = auth()->user()->last_login;

        // Get completed activities only if activities exist
        $completedActivities = 0;
        if ($totalActivities > 0) {
            $completedActivities = Activity::whereHas('users', function ($query) {
                $query->where('status', 'completed');
            })->count();
        }

        // Get upcoming activities if there are any
        $upcomingActivities = collect(); // empty collection by default
        if ($totalActivities > 0) {
            $upcomingActivities = Activity::where('start_date', '>=', now())
                ->orderBy('start_date')
                ->take(5)
                ->get();
        }

        return view('student.home', compact(
            'totalActivities',
            'completedActivities',
            'upcomingActivities',
            'last_login'
        ));
    }


    public function adminHome(): View

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

        return view('admin.home',  compact(
            'totalStudents',
            'totalActivities',
            'completedActivities',
            'pendingRequests',
            'upcomingActivities'
        ));
    }
}
