<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClassController extends Controller
{
    /**
     * Display a listing of classes (role-based).
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'lecturer') {
            $classes = Classroom::where('owner_id', $user->id)
                ->with(['owner', 'tasks' => function ($query) {
                    $query->orderBy('deadline_at', 'asc')->limit(1);
                }])
                ->withCount('participants')
                ->get();
        } else {
            $classes = $user->joinedClasses()
                ->with(['owner', 'tasks' => function ($query) {
                    $query->orderBy('deadline_at', 'asc')->limit(1);
                }])
                ->withCount('participants')
                ->get();
        }

        return view('lecturer.classes.index', compact('classes'));
    }


    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        $this->authorize('create', Classroom::class);
        return view('lecturer.classes.create');
    }

    /**
     * Store a newly created class.
     */
    public function store(ClassRequest $request)
    {
        $this->authorize('create', Classroom::class);

        $data = $request->validated();
        $data['owner_id'] = Auth::id();
        $data['join_code'] = Str::upper(Str::random(6));
        $data['schedule_day'] = $this->decodeScheduleDay($data['schedule_day'] ?? null);

        $class = Classroom::create($data);

        return redirect()
            ->route('classes.index')
            ->with('notification', [
                'status' => true,
                'message' => 'Kelas "' . $class->name . '" berhasil dibuat. Kode gabung: ' . $class->join_code,
            ]);
    }

    /**
     * Display the specified class (role-based view).
     */
    public function show(Classroom $class)
    {
        $user = Auth::user();

        $isOwner = $class->isOwner($user);
        $isParticipant = $class->isParticipant($user);

        if (!$isOwner && !$isParticipant) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $class->load([
            'owner',
            'tasks' => function ($query) {
                $query->orderBy('deadline_at', 'asc');
            },
            'announcements' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(5);
            },
            'participants'
        ]);

        $nearestTask = $class->tasks->first();
        $activeTasksCount = $class->tasks->filter(function ($task) {
            return $task->deadline_at->isFuture();
        })->count();

        if ($user->role === 'lecturer' && $isOwner) {
            return view('lecturer.classes.show', compact(
                'class',
                'isOwner',
                'isParticipant',
                'nearestTask',
                'activeTasksCount'
            ));
        }

        return view('student.classes.show', compact(
            'class',
            'isOwner',
            'isParticipant',
            'nearestTask',
            'activeTasksCount'
        ));
    }

    /**
     * Show the form for editing the class.
     */
    public function edit(Classroom $class)
    {
        $this->authorize('update', $class);
        return view('lecturer.classes.edit', compact('class'));
    }

    /**
     * Update the specified class.
     */
    public function update(ClassRequest $request, Classroom $class)
    {
        $this->authorize('update', $class);

        $data = $request->validated();
        $data['schedule_day'] = $this->decodeScheduleDay($data['schedule_day'] ?? null);

        $class->update($data);

        return redirect()
            ->route('classes.show', $class)
            ->with('notification', [
                'status' => true,
                'message' => 'Kelas "' . $class->name . '" berhasil diperbarui.',
            ]);
    }

    /**
     * Remove the specified class.
     */
    public function destroy(Classroom $class)
    {
        $this->authorize('delete', $class);

        $className = $class->name;
        $class->delete();

        return redirect()
            ->route('classes.index')
            ->with('notification', [
                'status' => true,
                'message' => 'Kelas "' . $className . '" berhasil dihapus.',
            ]);
    }

    /**
     * Decode schedule_day payload coming from the form.
     *
     * The form sends schedule_day as a JSON string (e.g. {"monday":"08:00-10:00"}).
     * Classroom casts schedule_day as 'array', so Eloquent will json_encode()
     * whatever is assigned to it. If we pass the raw JSON string through,
     * Eloquent encodes an already-encoded string, producing double-encoded
     * JSON in the database (and a value the edit form can no longer parse).
     * Decoding here ensures we always hand Eloquent a plain PHP array.
     */
    private function decodeScheduleDay($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    /**
     * Join a class using join_code (student only).
     */
    public function join(Request $request)
    {
        $request->validate([
            'join_code' => ['required', 'string', 'exists:classes,join_code'],
        ]);

        $user = Auth::user();

        if ($user->role === 'lecturer') {
            return back()->with('notification', [
                'status' => false,
                'message' => 'Dosen tidak dapat bergabung ke kelas melalui kode.',
            ]);
        }

        $class = Classroom::where('join_code', $request->join_code)->first();

        if ($class->isParticipant($user)) {
            return back()->with('notification', [
                'status' => false,
                'message' => 'Anda sudah tergabung di kelas ini.',
            ]);
        }

        $class->participants()->attach($user->id, ['joined_at' => now()]);

        return redirect()
            ->route('classes.show', $class)
            ->with('notification', [
                'status' => true,
                'message' => 'Berhasil bergabung ke kelas "' . $class->name . '".',
            ]);
    }

    /**
     * Leave a class (student only).
     */
    public function leave(Classroom $class)
    {
        $user = Auth::user();

        if ($user->role === 'lecturer') {
            return back()->with('notification', [
                'status' => false,
                'message' => 'Dosen tidak dapat keluar dari kelas yang dimilikinya.',
            ]);
        }

        if (!$class->isParticipant($user)) {
            return back()->with('notification', [
                'status' => false,
                'message' => 'Anda tidak tergabung di kelas ini.',
            ]);
        }

        $class->participants()->detach($user->id);

        return redirect()
            ->route('classes.index')
            ->with('notification', [
                'status' => true,
                'message' => 'Anda telah keluar dari kelas "' . $class->name . '".',
            ]);
    }
}