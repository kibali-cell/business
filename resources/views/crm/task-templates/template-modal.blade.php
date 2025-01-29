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
        <div class="modal fade" id="createTemplateModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Task Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('crm.task-templates.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Template Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Checklist Items</label>
                        <div id="checklistItems">
                            <div class="input-group mb-2">
                                <input type="text" name="checklist[]" class="form-control">
                                <button type="button" class="btn btn-outline-danger" onclick="removeChecklistItem(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addChecklistItem()">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Template</button>
                </div>
            </form>
        </div>
    </div>
</div>



        </div>

    @include('home.script')
    <script>
function addChecklistItem() {
    const newItem = `<div class="input-group mb-2">
        <input type="text" name="checklist[]" class="form-control">
        <button type="button" class="btn btn-outline-danger" onclick="removeChecklistItem(this)">
            <i class="fas fa-times"></i>
        </button>
    </div>`;
    document.getElementById('checklistItems').insertAdjacentHTML('beforeend', newItem);
}

function removeChecklistItem(button) {
    button.closest('.input-group').remove();
}
</script>
  </body>
</html>