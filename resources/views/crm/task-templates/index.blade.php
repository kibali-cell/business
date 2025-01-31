
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
                                    <i class="fas fa-use"></i> Use
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

        @include('crm.task-templates.template-modal')

    @include('home.script')

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

   
    
  </body>
</html>