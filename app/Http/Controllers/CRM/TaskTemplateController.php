<?php
namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\TaskTemplate;
use App\Models\Task;
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

    public function show($id)
    {
        try {
            $template = TaskTemplate::findOrFail($id);
            
            return response()->json([
                'name' => $template->name,
                'description' => $template->description,
                'checklist' => $template->checklist ?? []
            ]);
        } catch (\Exception $e) {
            \Log::error('Template show error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Template not found'
            ], 404);
        }
    }

    // Add missing destroy method
    public function destroy($id)
    {
        $template = TaskTemplate::findOrFail($id);
        $template->delete();
        return redirect()->route('crm.task-templates.index')->with('success', 'Template deleted successfully.');
    }
    
    public function use($id)
    {
        try {
            $template = TaskTemplate::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'template' => [
                    'name' => $template->name,
                    'description' => $template->description,
                    'checklist' => $template->checklist ?? []
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Template use error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Template not found'
            ], 404);
        }
    }


}