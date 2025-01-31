<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskTemplate;
use App\Services\TaskService;
use App\Notifications\TaskUpdated;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    // Display a list of tasks
    public function index()
    {
        $tasks = Task::with('assignedUser')->paginate(15); // Corrected: Removed ->get()
        $users = User::all(); // Fetch all users for the assignee dropdown
        $templates = TaskTemplate::all(); // Fetch all task templates
        return view('crm.tasks.index', compact('templates','tasks', 'users'));
    }

    // Show the form for creating a new task
    public function create()
    {
        $users = User::all(); // For assigning tasks to users
        $templates = TaskTemplate::all(); // For task templates
        return view('crm.tasks.create', compact('users', 'templates'));
    }

    // Store a newly created task in the database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'checklist' => 'array',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'template_id' => 'nullable|exists:task_templates,id',
        ]);


        // Use TaskService to handle task creation (including automatic assignment)
        $task = $this->taskService->assignTaskAutomatically($request->all());

        return redirect()->route('crm.tasks.index')->with('success', 'Task created successfully.');
    }

    // Display the specified task
    public function show(Task $task)
    {
        return view('crm.tasks.show', compact('task'));
    }

    // Show the form for editing the specified task
    public function edit(Task $task)
    {
        $users = User::all(); // For assigning tasks to users
        $templates = TaskTemplate::all(); // For task templates
        return view('crm.tasks.edit', compact('task', 'users', 'templates'));
    }

    // Update the specified task in the database
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'template_id' => 'nullable|exists:task_templates,id',
        ]);

        $task->update($request->all());

        return redirect()->route('crm.tasks.index')->with('success', 'Task updated successfully.');
    }

    // Update task status (for drag-and-drop functionality)
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        try {
            $task->update(['status' => $request->status]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Send notification to the assigned user
        if ($task->assignedUser) {
            $task->assignedUser->notify(new TaskUpdated($task));
        }


        return response()->json(['success' => true]);
    }

    
    // Remove the specified task from the database
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('crm.tasks.index')->with('success', 'Task deleted successfully.');
    }

    // Create a task from a template
    public function createFromTemplate(TaskTemplate $template)
    {
        $users = User::all(); // For assigning tasks to users
        return view('crm.tasks.create', [
            'users' => $users,
            'template' => $template,
            'checklist' => $template->checklist,
        ]);
    }
}