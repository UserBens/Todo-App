<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
        return view('tasks', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'due_date' => 'required|date',
            'priority' => 'required',
            'category' => 'required',
            'pic' => 'required',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tasks', 'public');
        }

        Task::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'category' => $request->category,
            'pic' => $request->pic,
            'file' => $filePath,
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
