<?php
namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\TaskTemplate;
use Illuminate\Http\Request;

class TaskTemplateController extends Controller
{
    public function index()
    {
        $templates = TaskTemplate::latest()->paginate(10);
        return view('crm.task-templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'checklist' => 'array'
        ]);

        TaskTemplate::create($validated);

        // Fix redirect to go back to templates list
        return redirect()->route('crm.task-templates.index')
            ->with('success', 'Template created successfully');
    }

    public function show(TaskTemplate $template)
    {
        // Keep this if you're using it for AJAX requests
        return response()->json($template);
    }

    


    // Add missing destroy method
    public function destroy(TaskTemplate $template)
    {
        $template->delete();
        return redirect()->route('crm.task-templates.index')
            ->with('success', 'Template deleted successfully');
    }
}