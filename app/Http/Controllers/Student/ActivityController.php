<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Admin\GoogleController;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityApplication;
use App\Models\ActivityUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::where('is_ended', false)->get();
        $user = auth()->user();
        $user->load('activities');


        // $appliedActivities = $user->activities->pluck('pivot.status', 'id')->toArray();
        $appliedActivities = $user->activityApplications
            ->pluck('status', 'activity_id')
            ->toArray();


        return view('student.activity-list', compact('activities', 'appliedActivities'));
    }

    public function apply($id)
    {


        $user = Auth::user();

        ActivityApplication::updateOrCreate([
            'user_id' => $user->id,
            'activity_id' => $id,
        ], [
            'status' => 'pending'
        ]);

        return back()->with('success', 'Application submitted.');
    }


    public function view($id)
    {

        $activity = Activity::findOrFail($id);
        $user = Auth::user();

        $application = ActivityApplication::where('user_id', $user->id)
            ->where('activity_id', $id)
            ->first();

        return view('student.activity-detail', compact('activity', 'application'));
    }

    public function cancel($id)
    {
        $user = Auth::user();

        ActivityApplication::where('user_id', $user->id)
            ->where('activity_id', $id)
            ->delete();

        return back()->with('success', 'Application cancelled.');
    }

    public function myActivity()
    {
        $student = Auth::user();

        // Assuming `activityApplications()` is a hasMany relation to application model
        $applications = $student->activityApplications()->with('activity')->get();

        return view('student.my-activity', compact('applications'));
    }

    public function syncCalendar()
    {
        $user = auth()->user();

        // Optional: check if user has synced too many times overall
        // $totalSyncs = ActivityApplication::where('user_id', $user->id)
        //     ->sum('calendar_synced');

        // if ($totalSyncs >= 3) {
        //     return back()->with('error', 'You have reached the maximum number of syncs.');
        // }

        if (!$user->google_token) {
            return redirect()->route('connect.google')->with('error', 'Please connect your Google Calendar first.');
        }

        $activities = Activity::whereHas('applications', function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->where('status', 'joined');
        })
            ->whereDate('start_date', '>=', now())
            ->get();

        $google = new GoogleController();

        foreach ($activities as $activity) {
            try {
                $google->createCalendarEvent(
                    $activity->title,
                    $activity->description ?? '',
                    $activity->location ?? '',
                    $activity->start_date,
                    $activity->end_date
                );

                // ✅ Update calendar_synced for this specific activity and user
                $application = ActivityApplication::where('user_id', $user->id)
                    ->where('activity_id', $activity->id)
                    ->first();

                if ($application) {
                    $application->calendar_synced++;
                    $application->save();
                }
            } catch (\Exception $e) {
                \Log::error("Calendar sync failed: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Your calendar has been synced.');
    }
}
