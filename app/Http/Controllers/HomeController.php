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
        return view('student.home');
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
