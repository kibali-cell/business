document.addEventListener('DOMContentLoaded', function () {
    // Event listener for the "use" button
    document.querySelectorAll('.use-template').forEach(button => {
        button.addEventListener('click', function () {
            const templateId = this.getAttribute('data-id');
            // Add your logic to handle the "use" action
            console.log('Use template with ID:', templateId);
            // Example: Redirect to a route
            window.location.href = `/crm/task-templates/use/${templateId}`;
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