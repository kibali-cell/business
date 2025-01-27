<div class="card mb-2 task-card" data-task-id="{{ $task->id }}">
    <div class="card-body">
        <h5 class="card-title">{{ $task->title }}</h5>
        <p class="card-text text-muted">{{ Str::limit($task->description, 100) }}</p>
        
        <div class="d-flex justify-content-between align-items-center">
            <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }}">
                {{ ucfirst($task->priority) }}
            </span>
            <small>Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</small>
        </div>

        <div class="mb-3">
    <label class="form-label">Attachments</label>
    <div id="documentsList" class="mt-2">
        @foreach($task->documents as $document)

        <div class="document-item" data-id="{{ $document->id }}">
            <a href="{{ Storage::url($document->path) }}" target="_blank">
                {{ $document->filename }}
            </a>
            <button type="button" onclick="deleteDocument({{ $document->id }})" class="btn btn-sm btn-danger">
                Delete
            </button>
        </div>

        @endforeach
    </div>
</div>
        
        <div class="mt-2 d-flex justify-content-between align-items-center">
            <small class="text-muted">Assigned to: {{ $task->assignee->name }}</small>
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Actions
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="editTask({{ $task->id }})">Edit</a></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteTask({{ $task->id }})">Delete</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>