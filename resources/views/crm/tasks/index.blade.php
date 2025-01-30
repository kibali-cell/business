<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <style>
      .task-card {
          border-radius: 8px;
          border: 1px solid rgba(0,0,0,.125);
          transition: transform 0.15s ease-in-out;
      }

      .task-card:hover {
          transform: translateY(-2px);
      }

      .task-card .badge {
          font-weight: normal;
          font-size: 0.75rem;
          padding: 0.25em 0.75em;
      }

      .task-card .card-title {
          font-weight: 500;
          line-height: 1.2;
      }

      .task-card .btn-light {
          background-color: #f8f9fa;
          border: 1px solid #dee2e6;
      }

      .dropdown-menu {
          font-size: 0.875rem;
          min-width: 8rem;
      }

      .dropdown-item {
          padding: 0.4rem 1rem;
      }

      .dropdown-item i {
          width: 1rem;
          text-align: center;
          margin-right: 0.5rem;
      }
    </style>
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      @include('home.header')
      
      <div class="container-fluid page-body-wrapper">
        @include('home.sidebar')

        <div class="container-fluid">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Task Board</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
              <i class="fas fa-plus"></i> Add Task
            </button>
          </div>

          <div class="row">
            <!-- Pending Tasks Column -->
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-header bg-secondary text-white">
                  Pending Tasks
                </div>
                <div class="card-body">
                  <div class="task-list" id="pending" data-status="pending">
                    @foreach($tasks->where('status', 'pending') as $task)
                    <div class="card mb-2 shadow-sm task-card" data-task-id="{{ $task->id }}">
                        <div class="card-body p-3">
                            <h5 class="card-title fs-6 mb-2">{{ $task->title }}</h5>
                            <p class="card-text text-muted small mb-3">{{ Str::limit($task->description, 100) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge rounded-pill bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                <small class="text-muted">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</small>
                            </div>

                            @if($task->documents && $task->documents->count() > 0)
                                <div class="mb-3">
                                    <div class="text-muted small">Attachments</div>
                                    <div class="mt-2">
                                        @foreach($task->documents as $document)
                                            <div class="document-item d-flex align-items-center gap-2 mb-1">
                                                <a href="{{ Storage::url($document->path) }}" target="_blank" class="text-decoration-none small">
                                                    {{ $document->filename }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Assigned to: {{ $task->assignedUser->name }}</small>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle px-2 py-1" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('crm.tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item small text-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <!-- In Progress Column -->
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-header bg-primary text-white">
                  In Progress
                </div>
                <div class="card-body">
                  <div class="task-list" id="in_progress" data-status="in_progress">
                    @foreach($tasks->where('status', 'in_progress') as $task)
                    <div class="card mb-2 shadow-sm task-card" data-task-id="{{ $task->id }}">
                        <div class="card-body p-3">
                            <h5 class="card-title fs-6 mb-2">{{ $task->title }}</h5>
                            <p class="card-text text-muted small mb-3">{{ Str::limit($task->description, 100) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge rounded-pill bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                <small class="text-muted">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</small>
                            </div>

                            @if($task->documents && $task->documents->count() > 0)
                                <div class="mb-3">
                                    <div class="text-muted small">Attachments</div>
                                    <div class="mt-2">
                                        @foreach($task->documents as $document)
                                            <div class="document-item d-flex align-items-center gap-2 mb-1">
                                                <a href="{{ Storage::url($document->path) }}" target="_blank" class="text-decoration-none small">
                                                    {{ $document->filename }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Assigned to: {{ $task->assignedUser->name }}</small>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle px-2 py-1" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('crm.tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item small text-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <!-- Completed Column -->
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-header bg-success text-white">
                  Completed
                </div>
                <div class="card-body">
                  <div class="task-list" id="completed" data-status="completed">
                    @foreach($tasks->where('status', 'completed') as $task)
                    <div class="card mb-2 shadow-sm task-card" data-task-id="{{ $task->id }}">
                        <div class="card-body p-3">
                            <h5 class="card-title fs-6 mb-2">{{ $task->title }}</h5>
                            <p class="card-text text-muted small mb-3">{{ Str::limit($task->description, 100) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge rounded-pill bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                <small class="text-muted">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</small>
                            </div>

                            @if($task->documents && $task->documents->count() > 0)
                                <div class="mb-3">
                                    <div class="text-muted small">Attachments</div>
                                    <div class="mt-2">
                                        @foreach($task->documents as $document)
                                            <div class="document-item d-flex align-items-center gap-2 mb-1">
                                                <a href="{{ Storage::url($document->path) }}" target="_blank" class="text-decoration-none small">
                                                    {{ $document->filename }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Assigned to: {{ $task->assignedUser->name }}</small>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle px-2 py-1" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('crm.tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item small text-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Create Task Modal -->
        <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="{{ route('crm.tasks.store') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                  </div>
                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                  </div>
                  <!-- Add this after description field in createTaskModal -->
                  <div class="mb-3">
                      <label class="form-label">Checklist</label>
                      <div id="taskChecklist"></div>
                  </div>
                  <div class="mb-3">
                    <label for="assigned_to" class="form-label">Assigned To</label>
                    <select class="form-control" id="assigned_to" name="assigned_to">
                      @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                      <option value="pending">Pending</option>
                      <option value="in_progress">In Progress</option>
                      <option value="completed">Completed</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="priority" class="form-label">Priority</label>
                    <select class="form-control" id="priority" name="priority">
                      <option value="low">Low</option>
                      <option value="medium">Medium</option>
                      <option value="high">High</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="due_date" class="form-label">Due Date</label>
                    <input type="date" class="form-control" id="due_date" name="due_date">
                  </div>
                  <div class="mb-3">
                      <label>Use Template</label>
                      <select class="form-select" id="templateSelect">
                          <option value="">Select Template</option>
                          @foreach($templates as $template)
                              <option value="{{ $template->id }}">{{ $template->name }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Task</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Edit Task Modals -->
        @foreach ($tasks as $task)
          <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="editTaskModalLabel{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editTaskModalLabel{{ $task->id }}">Edit Task</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="{{ route('crm.tasks.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                      <label for="edit-title" class="form-label">Title</label>
                      <input type="text" class="form-control" id="edit-title" name="title" value="{{ $task->title }}" required>
                    </div>
                    <div class="mb-3">
                      <label for="edit-description" class="form-label">Description</label>
                      <textarea class="form-control" id="edit-description" name="description" rows="3">{{ $task->description }}</textarea>
                    </div>
                    <div class="mb-3">
                      <label for="edit-assigned_to" class="form-label">Assigned To</label>
                      <select class="form-control" id="edit-assigned_to" name="assigned_to">
                        @foreach ($users as $user)
                          <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="edit-status" class="form-label">Status</label>
                      <select class="form-control" id="edit-status" name="status">
                        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="edit-priority" class="form-label">Priority</label>
                      <select class="form-control" id="edit-priority" name="priority">
                        <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="edit-due_date" class="form-label">Due Date</label>
                      <input type="date" class="form-control" id="edit-due_date" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @endforeach


        <!-- end of new modals -->
      </div>
    </div>

    @include('home.script')
    <script>
      // Task List Script
      document.querySelectorAll('.task-list').forEach((el) => {
    new Sortable(el, {
      group: 'tasks',
      animation: 150,
      onEnd: function (evt) {
        const taskId = evt.item.dataset.taskId;
        const newStatus = evt.to.dataset.status;

        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('status', newStatus);

        fetch(`/crm/tasks/${taskId}`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: formData,
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then((data) => {
            console.log('Task status updated:', data);
          })
          .catch((error) => {
            console.error('Error:', error);
            // Revert the DOM changes
            evt.to.removeChild(evt.item);
            const refNode = evt.from.children[evt.oldIndex] || null;
            evt.from.insertBefore(evt.item, refNode);
          });
      },
    });
  });
    </script>
    <script>
      // Initialize SortableJS for task boards
      // Template selection handler
document.getElementById('templateSelect').addEventListener('change', function() {
    if (this.value) {
        // Add console.log to debug the request
        console.log('Fetching template with ID:', this.value);
        
        fetch(`/crm/task-templates/${this.value}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Debug: Log the received data
                console.log('Received template data:', data);
                
                // Get the form elements
                const titleInput = document.getElementById('title');
                const descriptionInput = document.getElementById('description');
                
                // Debug: Log the elements we're trying to populate
                console.log('Title input element:', titleInput);
                console.log('Description input element:', descriptionInput);
                
                // Safely populate the fields
                if (titleInput && data.name) {
                    titleInput.value = data.name;
                    console.log('Set title to:', data.name);
                }
                
                if (descriptionInput && data.description) {
                    descriptionInput.value = data.description || '';
                    console.log('Set description to:', data.description);
                }
                
                // Handle checklist if it exists
                const checklistContainer = document.getElementById('taskChecklist');
                if (checklistContainer && data.checklist && Array.isArray(data.checklist)) {
                    checklistContainer.innerHTML = ''; // Clear existing items
                    
                    data.checklist.forEach(item => {
                        const newItem = `
                            <div class="input-group mb-2">
                                <input type="text" name="checklist[]" class="form-control" value="${item}">
                                <button type="button" class="btn btn-outline-danger" onclick="removeChecklistItem(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        checklistContainer.insertAdjacentHTML('beforeend', newItem);
                    });
                    console.log('Updated checklist items');
                }
            })
            .catch(error => {
                console.error('Error loading template:', error);
                alert('Failed to load template. Please check the console for details.');
            });
    }
});

function removeChecklistItem(button) {
    button.closest('.input-group').remove();
}

function addChecklistItem() {
    const checklistContainer = document.getElementById('taskChecklist');
    const newItem = `
        <div class="input-group mb-2">
            <input type="text" name="checklist[]" class="form-control" placeholder="New item">
            <button type="button" class="btn btn-outline-danger" onclick="removeChecklistItem(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    checklistContainer.insertAdjacentHTML('beforeend', newItem);
}
    </script>

    
  </body>
</html>