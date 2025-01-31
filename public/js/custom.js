document.addEventListener('DOMContentLoaded', function () {
    // Event listener for the "use" button
    document.querySelectorAll('.use-template').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const templateId = this.dataset.id;
            
            fetch(`/crm/task-templates/${templateId}/use`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const template = data.template;
                        const modalElement = document.getElementById('taskCreateModal');
                        
                        const titleInput = modalElement.querySelector('input[name="title"]');
                        const descTextarea = modalElement.querySelector('textarea[name="description"]');
                        const checklistContainer = modalElement.querySelector('#taskChecklist');
    
                        if (titleInput) titleInput.value = template.name || '';
                        if (descTextarea) descTextarea.value = template.description || '';
                        
                        if (checklistContainer) {
                            checklistContainer.innerHTML = '';
                            (template.checklist || []).forEach(item => {
                                const itemHTML = `
                                    <div class="input-group mb-2">
                                        <input type="text" name="checklist[]" 
                                               class="form-control" 
                                               value="${item}">
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="this.parentElement.remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>`;
                                checklistContainer.insertAdjacentHTML('beforeend', itemHTML);
                            });
                        }
    
                        // Show modal
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load template');
                });
        });
    });

    // Event listener for the "delete" button
    document.querySelectorAll('.delete-template-form').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this template?')) {
                this.submit();
            }
        });
    });
});