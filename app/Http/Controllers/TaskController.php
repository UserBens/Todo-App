<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('tasks', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'due_date' => 'required|date',
            'category' => 'required|in:fjm mobile,networking,desain,website,hardware,software,device'
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'category' => $request->category
        ]);

        return redirect()->back()->with('success', 'Task berhasil ditambahkan!');
    }

    public function complete($id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->back()->with('success', 'Task berhasil diselesaikan!');
    }

    // Hapus task
    public function delete($id)
    {
        Task::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Task berhasil dihapus!');
    }
}
