<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewAnnouncementNotification;
use App\Models\Announcement;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements (student view).
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'student') {
            $announcements = Announcement::whereHas('class', function ($query) use ($user) {
                $query->whereHas('participants', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->with('class')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($announcement) {
                return $announcement->isActive();
            });

            return view('student.announcements.index', compact('announcements'));
        }

        $announcements = Announcement::whereHas('class', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })
        ->with('class')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('lecturer.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(Classroom $class)
    {
        $this->authorize('create', [Announcement::class, $class]);

        return view('lecturer.announcements.create', compact('class'));
    }

    /**
     * Store a newly created announcement.
     */
     public function store(AnnouncementRequest $request, Classroom $class)
    {
        $this->authorize('create', [Announcement::class, $class]);

        $data = $request->validated();
        $data['class_id'] = $class->id;

        $announcement = $class->announcements()->create($data);

        $students = $class->participants()->where('role', 'student')->get();

        Notification::send($students, new NewAnnouncementNotification($announcement, $class));

        return redirect()
            ->route('classes.show', $class)
            ->with('notification', [
                'status' => true,
                'message' => 'Pengumuman "' . $announcement->title . '" berhasil dibuat dan notifikasi telah dikirim.',
            ]);
    }

    /**
     * Show the form for editing the announcement.
     */
    public function edit(Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        $class = $announcement->class;

        return view('lecturer.announcements.edit', compact('class', 'announcement'));
    }

    /**
     * Update the specified announcement.
     */
    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        $announcement->update($request->validated());

        return redirect()
            ->route('classes.show', $announcement->class)
            ->with('notification', [
                'status' => true,
                'message' => 'Pengumuman "' . $announcement->title . '" berhasil diperbarui.',
            ]);
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Announcement $announcement)
    {
        $this->authorize('delete', $announcement);

        $title = $announcement->title;
        $class = $announcement->class;
        $announcement->delete();

        return redirect()
            ->route('classes.show', $class)
            ->with('notification', [
                'status' => true,
                'message' => 'Pengumuman "' . $title . '" berhasil dihapus.',
            ]);
    }
}