<div class="document-list">
    @if($documents->isEmpty())
        <div class="alert alert-info">No documents found in this folder.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Version</th>
                        <th>Folder</th>
                        <th>Uploaded By</th>
                        <th>Upload Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                    <tr>
                        <td>
                            <i class="fas fa-file me-2 text-primary"></i>
                            {{ $document->title }}
                        </td>
                        <td>{{ $document->currentVersion->version }}</td>
                        <td>
                            @if($document->folder)
                                <i class="fas fa-folder me-2 text-warning"></i>
                                {{ $document->folder->name }}
                            @else
                                <span class="text-muted">Root</span>
                            @endif
                        </td>
                        <td>{{ $document->uploader->name }}</td>
                        <td>{{ $document->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('documents.show', $document) }}" 
                               class="btn btn-sm btn-primary"
                               title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ Storage::url($document->currentVersion->path) }}" 
                               class="btn btn-sm btn-success ms-1"
                               download
                               title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            @can('delete', $document)
                            <form class="d-inline ms-1" 
                                  action="{{ route('documents.destroy', $document) }}" 
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-danger"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($documents->hasPages())
        <div class="mt-4">
            {{ $documents->links() }}
        </div>
        @endif
    @endif
</div>

<style>
    .document-list table tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .document-list table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .document-list .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
</style>