<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Google\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Activity::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_ended', false);
            } elseif ($request->status == 'ended') {
                $query->where('is_ended', true);
            }
        }

        $activity_list = $query->orderBy('start_date', 'desc')->get();

        return view('admin.activity-list', compact('activity_list'));
    }


    public function show(string $id)
    {
        $activity = Activity::findOrFail($id);
        $applications = \App\Models\ActivityApplication::with('user')
            ->where('activity_id', $activity->id)
            ->get();
        $pendingStudents = $applications->where('status', 'pending');
        $acceptedStudents = $applications->where('status', 'joined');
        // dd($pendingStudents);
        return view('admin.activity-detail', compact('activity', 'pendingStudents', 'acceptedStudents'));
    }

    public function uploadQr(Request $request, $id)
    {
        $request->validate([
            'qr_code' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $activity = Activity::findOrFail($id);

        // Delete old QR if exists
        if ($activity->qr_code && Storage::disk('public')->exists('qr_codes/' . $activity->qr_code)) {
            Storage::disk('public')->delete('qr_codes/' . $activity->qr_code);
        }

        // Save the uploaded file
        if ($request->hasFile('qr_code')) {
            $qrName = time() . '_' . $request->file('qr_code')->getClientOriginalName();
            $path = $request->file('qr_code')->storeAs('qr_codes', $qrName, 'public');

            $activity->qr_code = $path;
            $activity->save();
        }

        return back()->with('success', 'QR code uploaded successfully.');
    }


    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        return view('admin.activity-edit', compact('activity'));
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:seminar,workshop,competition,volunteering,talk,conference,training,webinar,sports,other',
            'location' => 'required|string',
        ]);

        $activity->update($request->all());

        return redirect()->route('activities.show', $id)->with('success', 'Activity updated.');
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);

        // Delete QR image if exists
        if ($activity->qr_code && Storage::disk('public')->exists('qrcodes/' . $activity->qr_code)) {
            Storage::disk('public')->delete('qrcodes/' . $activity->qr_code);
        }

        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Activity deleted.');
    }

    public function end($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->is_ended = true;
        $activity->save();

        return redirect()->route('activities.show', $id)->with('success', 'Activity has ended.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'type' => 'required|in:seminar,workshop,competition,volunteering,talk,conference,training,webinar,sports,other',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('qr_code')) {
            $filename = time() . '_' . $request->file('qr_code')->getClientOriginalName();
            $path = $request->file('qr_code')->storeAs('qr_codes', $filename, 'public');
            $validated['qr_code'] = $path;
        }

        $validated['is_ended'] = false;

        Activity::create($validated);

        return redirect()->route('activities.index')->with('success', 'Activity created successfully!');
    }

    public function create()
    {

        return view('admin.activity-add');
    }


    public function acceptStudent($activityId, $applicationId): RedirectResponse
    {
        try {
            $application = ActivityApplication::where('activity_id', $activityId)
                ->where('id', $applicationId)
                ->firstOrFail();

            $activity = $application->activity;
            $user = auth()->user();
            // if (!$user || !$user->google_token) {
            //     return redirect()->back()->with('error', 'Please connect to Google Calendar first.');
            // }

            // Log::info('User attempting calendar add:', [
            //     'user_id' => $user ? $user->id : 'not authenticated',
            //     'has_google_token' => $user && $user->google_token ? 'yes' : 'no',
            //     'activity' => $activity->title
            // ]);

            // Try creating the calendar event first
            // $google = new GoogleController();
            // $google = app(GoogleController::class);
            // $google->createCalendarEvent(
            //     $activity->title,
            //     $activity->description,
            //     $activity->location,
            //     $activity->start_date . 'T09:00:00',
            //     $activity->end_date . 'T17:00:00'
            // );

            // Log::info('Calendar event created', ['result' => $google]);
            // Only set to joined if calendar was created successfully
            $application->status = 'joined';
            $application->save();

            return redirect()->back()->with('success', 'Student accepted to the activity.');
        } catch (\Exception $e) {
            Log::error('Error accepting student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to accept student. Nothing was changed.');
        }
    }

    public function rejectStudent($activityId, $applicationId): RedirectResponse
    {
        try {
            $application = ActivityApplication::where('activity_id', $activityId)
                ->where('id', $applicationId)
                ->firstOrFail();

            $application->status = 'cancelled';
            if (!$application->save()) {
                return redirect()->back()->with('error', 'Failed to update application status.');
            }

            return redirect()->back()->with('success', 'Student rejected successfully.');
        } catch (\Exception $e) {
            Log::error('Error rejecting student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while rejecting the student.');
        }
    }
}
