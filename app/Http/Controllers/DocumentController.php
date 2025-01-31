<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Document, DocumentVersion, Folder};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
   
    
    public function index()
    {
        $documents = auth()->user()->accessibleDocuments()
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->paginate(15);

        $folders = Folder::with('children')->whereNull('parent_id')->get();
        
        return view('documents.index', compact('documents', 'folders'));
    }



    // DocumentController.php
    public function create()
    {
        return view('documents.create', [
            'currentFolder' => request()->input('folder_id'),
            'folders' => Folder::tree()->get()
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:2048|mimes:pdf,doc,docx,xls,xlsx,txt',
            'folder_id' => 'nullable|exists:folders,id',
            'status' => 'sometimes|in:draft,published' // Add validation
        ]);
    
        $file = $request->file('file');
        $path = $file->store('documents', 'private');
    
        $document = Document::create([
            'title' => $request->title,
            'folder_id' => $request->folder_id,
            'uploaded_by' => auth()->id(),
            'status' => $request->status ?? 'published' // Default status
        ]);

        DocumentVersion::create([
            'document_id' => $document->id,
            'path' => $path,
            'version' => '1.0',
            'uploaded_by' => auth()->id()
        ]);

        return redirect()->route('documents.index')->with('success', 'Document uploaded!');
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);
        
        return view('documents.show', [
            'document' => $document->load(['versions', 'users']),
            'versions' => $document->versions()->orderByDesc('created_at')->get()
        ]);
    }

    public function edit(Document $document)
    {
        // Authorization check
        $this->authorize('update', $document);
        
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
{
    $this->authorize('edit', $document);

    $request->validate([
        'file' => 'required|file|max:2048|mimes:pdf,doc,docx,xls,xlsx,txt',
        'changes' => 'nullable|string',
        'status' => 'sometimes|in:draft,published,archived' // Add status update
    ]);

    // Update document status if provided
    if ($request->has('status')) {
        $document->update(['status' => $request->status]);
    }

    // File handling remains the same
    $latestVersion = (float) optional($document->currentVersion)->version ?? 0;
    
    DocumentVersion::create([
        'document_id' => $document->id,
        'path' => $path,
        'version' => $latestVersion + 0.1,
        'changes' => $request->changes,
        'uploaded_by' => auth()->id()
    ]);

    return back()->with('success', 'Document updated!');
}

    public function manageAccess(Request $request, Document $document)
    {
        $this->authorize('manage', $document);

        $request->validate([
            'users' => 'required|array',
            'users.*.id' => 'required|exists:users,id',
            'users.*.permission' => 'required|in:view,edit,manage'
        ]);

        $document->users()->sync(
            collect($request->users)
                ->mapWithKeys(fn($user) => [$user['id'] => ['permission' => $user['permission']]])
        );

        return back()->with('success', 'Permissions updated!');
    }

    public function destroy(Document $document)
{
    $this->authorize('delete', $document);
    // Delete logic
}
}
