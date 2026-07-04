<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Task;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'lecturer') {
            return redirect()->route('dashboard.lecturer');
        }

        return redirect()->route('dashboard.student');
    }

    public function lecturerDashboard()
    {
        $user = auth()->user();

        $totalClasses = Classroom::where('owner_id', $user->id)->count();

        $totalActiveTasks = Task::whereHas('class', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->where('status', 'Active')->count();

        $totalAnnouncements = Announcement::whereHas('class', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->count();

        return view('lecturer.dashboard', compact(
            'totalClasses',
            'totalActiveTasks',
            'totalAnnouncements'
        ));
    }

    public function studentDashboard()
    {
        $user = auth()->user();

        $totalClasses = $user->joinedClasses()->count();

        $totalUpcomingTasks = Task::whereHas('class', function ($query) use ($user) {
            $query->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })->where('deadline_at', '>', now())->count();

        $totalAnnouncements = Announcement::whereHas('class', function ($query) use ($user) {
            $query->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })->where(function($q) {
            $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
        })->count();

        return view('student.dashboard', compact(
            'totalClasses',
            'totalUpcomingTasks',
            'totalAnnouncements'
        ));
    }
}