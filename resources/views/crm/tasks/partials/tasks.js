// Initialize Sortable for each task list
document.addEventListener('DOMContentLoaded', function() {
    // Get all task lists
    const taskLists = document.querySelectorAll('.task-list');
    
    // Initialize Sortable for each list
    taskLists.forEach(taskList => {
        new Sortable(taskList, {
            group: 'shared',
            animation: 150,
            ghostClass: 'bg-light',
            dragClass: 'shadow',
            handle: '.card-body', // Make tasks draggable by their body
            chosenClass: 'task-chosen',
            forceFallback: true, // Better mobile support
            fallbackClass: 'task-dragging',
            
            // Called when dragging starts
            onStart: function(evt) {
                const item = evt.item;
                item.classList.add('shadow-lg');
            },
            
            // Called when dragging ends
            onEnd: function(evt) {
                const item = evt.item;
                item.classList.remove('shadow-lg');
                
                // Get necessary data
                const taskId = item.dataset.taskId;
                const newStatus = evt.to.dataset.status;
                const oldStatus = evt.from.dataset.status;
                
                // Only update if the status actually changed
                if (newStatus !== oldStatus) {
                    updateTaskStatus(taskId, newStatus);
                }
            }
        });
    });
});

// Function to update task status
function updateTaskStatus(taskId, newStatus) {
    // Show loading state
    const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);
    const originalContent = taskCard.innerHTML;
    taskCard.style.opacity = '0.6';
    
    // Prepare the request
    const url = `/crm/tasks/${taskId}/update-status`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Success handling
        taskCard.style.opacity = '1';
        
        // Show success feedback
        const toast = createToast('Success', 'Task status updated successfully', 'success');
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Restore original position
        const originalList = document.querySelector(`[data-status="${oldStatus}"]`);
        if (originalList) {
            originalList.appendChild(taskCard);
        }
        
        // Show error feedback
        const toast = createToast('Error', 'Failed to update task status', 'danger');
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}

// Helper function to create toast notifications
function createToast(title, message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast position-fixed top-0 end-0 m-3 bg-${type} text-white`;
    toast.style.zIndex = '1050';
    toast.innerHTML = `
        <div class="toast-header bg-${type} text-white">
            <strong class="me-auto">${title}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;
    return toast;
}

// Add touch event handling for better mobile support
document.addEventListener('touchmove', function(e) {
    if (e.target.closest('.task-dragging')) {
        e.preventDefault();
    }
}, { passive: false });