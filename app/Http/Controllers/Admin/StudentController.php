<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('type', 0)
            ->with(['activityApplications.activity'])
            ->get();
        // dd($students);
        return view('admin.student-list', compact('students'));
    }
}
