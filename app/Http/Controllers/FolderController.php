<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Document, DocumentVersion, Folder};

class FolderController extends Controller
{
    //
    public function index()
    {
        $folders = Folder::with('documents')->get();
        return view('folders.index', compact('folders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id'
        ]);

        Folder::create([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'],
            'created_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Folder created successfully');
    }

    
    public function show(Folder $folder)
    {
        $documents = $folder->documents()
            ->with(['currentVersion', 'uploader'])
            ->paginate(15);

        return view('folders.show', [
            'folder' => $folder,
            'documents' => $documents,
            'breadcrumbs' => $this->getBreadcrumbs($folder)
        ]);
    }

    private function getBreadcrumbs(Folder $folder)
    {
        $breadcrumbs = collect();
        $current = $folder;
        
        while($current) {
            $breadcrumbs->prepend([
                'id' => $current->id,
                'name' => $current->name
            ]);
            $current = $current->parent;
        }
        
        return $breadcrumbs;
    }

    public function destroy(Folder $folder)
    {
        $folder->children->each(fn($child) => $this->destroy($child));
        $folder->documents()->delete();
        $folder->delete();
        
        return redirect()->back()->with('success', 'Folder deleted');
    }
}
