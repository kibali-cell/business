<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Include SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      @include('home.header')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        @include('home.sidebar')
        <!-- partial -->

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
                      <div class="card mb-3" data-task-id="{{ $task->id }}">
                        <div class="card-body">
                          <h5 class="card-title">{{ $task->title }}</h5>
                          <p class="card-text">{{ $task->description }}</p>
                          <small class="text-muted">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</small>
                          <div class="mt-2">
                            <small class="text-muted">Assigned to: {{ $task->assignedUser->name }}</small>
                          </div>
                          <div class="mt-2">
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                              <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('crm.tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                              </button>
                            </form>
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
                      <div class="card mb-3" data-task-id="{{ $task->id }}">
                        <div class="card-body">
                          <h5 class="card-title">{{ $task->title }}</h5>
                          <p class="card-text">{{ $task->description }}</p>
                          <small class="text-muted">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</small>
                          <div class="mt-2">
                            <small class="text-muted">Assigned to: {{ $task->assignedUser->name }}</small>
                          </div>
                          <div class="mt-2">
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                              <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('crm.tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                              </button>
                            </form>
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
                      <div class="card mb-3" data-task-id="{{ $task->id }}">
                        <div class="card-body">
                          <h5 class="card-title">{{ $task->title }}</h5>
                          <p class="card-text">{{ $task->description }}</p>
                          <small class="text-muted">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</small>
                          <div class="mt-2">
                            <small class="text-muted">Assigned to: {{ $task->assignedUser->name }}</small>
                          </div>
                          <div class="mt-2">
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                              <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('crm.tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                              </button>
                            </form>
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

      </div>
    </div>

    @include('home.script')
    <script>
      // Initialize SortableJS for task boards
      document.addEventListener('DOMContentLoaded', function () {
        const taskLists = document.querySelectorAll('.task-list');
        taskLists.forEach(list => {
          new Sortable(list, {
            group: 'tasks',
            animation: 150,
            onEnd: function (evt) {
              const taskId = evt.item.dataset.taskId;
              const newStatus = evt.to.id;
              fetch(`/tasks/${taskId}/update-status`, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus })
              });
            }
          });
        });
      });
    </script>
  </body>
</html>