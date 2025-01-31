
<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
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
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Task Templates</h5>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                <i class="fas fa-plus"></i> New Template
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Checklist Items</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td>{{ Str::limit($template->description, 50) }}</td>
                            <td>
                                @if($template->checklist)
                                    <ul class="mb-0">
                                        @foreach($template->checklist as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary use-template" 
                                        data-id="{{ $template->id }}">
                                    <i class="fas fa-play"></i> Use
                                </button>
                                <form action="{{ route('crm.task-templates.destroy', $template->id) }}" method="POST" class="d-inline delete-template-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $templates->links() }}
            </div>
        </div>
    </div>
</div>
        </div>

        <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Create Task from Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('crm.tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Checklist</label>
                        <div id="taskChecklist"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Add this at the bottom of your task-templates/index.blade.php -->
<div class="modal fade" id="taskCreateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Task from Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('crm.tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div id="taskChecklist"></div>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="taskCreateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Task from Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('crm.tasks.store') }}" method="POST">
                    @csrf
                    <!-- Changed from name="name" to name="title" -->
                    <div class="mb-3">
                        <label>Task Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Checklist Items</label>
                        <div id="taskChecklist">
                            <!-- Dynamic items will be here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        @include('crm.task-templates.template-modal')

    
    <script src="{{ asset('js/custom.js') }}"></script>
    <script>
        document.getElementById('templateSelect').addEventListener('change', function() {
    if (this.value) {
        fetch(`/crm/task-templates/${this.value}`)
            .then(response => response.json())
            .then(template => {
                console.log("Fetched Template:", template); // Debugging line to check response
                
                // Ensure selectors correctly target input fields
                let titleField = document.querySelector('input[name="title"]');
                let descriptionField = document.querySelector('textarea[name="description"]');
                
                if (titleField) {
                    titleField.value = template.name || ''; // Ensure undefined doesn't break code
                } else {
                    console.error("Title field not found!");
                }

                if (descriptionField) {
                    descriptionField.value = template.description || '';
                } else {
                    console.error("Description field not found!");
                }

                // Handle checklist population
                const checklistContainer = document.getElementById('taskChecklist');
                if (checklistContainer) {
                    checklistContainer.innerHTML = '';

                    if (template.checklist && Array.isArray(template.checklist)) {
                        template.checklist.forEach(item => {
                            const newItem = `<div class="input-group mb-2">
                                <input type="text" name="checklist[]" class="form-control" value="${item}">
                                <button type="button" class="btn btn-outline-danger" onclick="removeChecklistItem(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>`;
                            checklistContainer.insertAdjacentHTML('beforeend', newItem);
                        });
                    }
                } else {
                    console.error("Checklist container not found!");
                }
            })
            .catch(error => console.error("Error fetching template:", error));
    }
});



    </script>

@include('home.script')
    
  </body>
</html>