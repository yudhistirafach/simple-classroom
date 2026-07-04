<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Classroom;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Classroom $class)
    {
        $this->authorize('viewAny', [Task::class, $class]);

        $tasks = $class->tasks()->orderBy('deadline_at')->get();

        return view('lecturer.tasks.index', compact('class', 'tasks'));
    }

    public function create(Classroom $class)
    {
        $this->authorize('create', [Task::class, $class]);

        return view('lecturer.tasks.create', compact('class'));
    }

    public function store(TaskRequest $request, Classroom $class)
    {
        $this->authorize('create', [Task::class, $class]);

        $data = $request->validated();
        $data['class_id'] = $class->id;
        $data['status'] = 'Active'; 

        $task = $class->tasks()->create($data);

        return redirect()
            ->route('classes.show', $class)
            ->with('notification', [
                'status' => true,
                'message' => 'Tugas "' . $task->title . '" berhasil dibuat.',
            ]);
    }


    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $class = $task->class;
        return view('lecturer.tasks.edit', compact('class', 'task'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());
        return redirect()
            ->route('classes.show', $task->class)
            ->with('notification', [
                'status' => true,
                'message' => 'Tugas "' . $task->title . '" berhasil diperbarui.'
            ]);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $title = $task->title;
        $class = $task->class;
        $task->delete();

        return redirect()
            ->route('classes.show', $class)
            ->with('notification', [
                'status' => true,
                'message' => 'Tugas "' . $title . '" berhasil dihapus.',
            ]);
    }

    public function studentIndex()
    {
        $user = auth()->user();
        $tasks = Task::whereHas('class', function ($query) use ($user) {
            $query->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })
        ->where('deadline_at', '>', now()) 
        ->orderBy('deadline_at', 'asc')
        ->with('class')
        ->get();

        return view('student.tasks.index', compact('tasks'));
    }
}