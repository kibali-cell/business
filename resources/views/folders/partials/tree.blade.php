=
    <style>
    .folder-tree {
        list-style-type: none;
        padding-left: 0;
    }
    
    .folder-item {
        transition: all 0.2s ease;
        background-color: #f8f9fa;
    }
    
    .folder-item:hover {
        background-color: #e9ecef;
        transform: translateX(3px);
    }
    
    .folder-item.active {
        background-color: #0d6efd;
        color: white;
    }
</style>
        <ul class="folder-tree list-unstyled">
    @foreach($folders as $folder)
        <li class="mb-1">
            <div class="folder-item d-flex align-items-center py-1 px-2 rounded"
                 data-folder-id="{{ $folder->id }}"
                 role="button">
                <i class="fas fa-folder me-2 text-warning"></i>
                <span class="folder-name">{{ $folder->name }}</span>
                @if($folder->children->isNotEmpty())
                    <span class="badge bg-secondary ms-auto">
                        {{ $folder->children->count() }}
                    </span>
                @endif
            </div>
            
            @if($folder->children->isNotEmpty())
                <div class="ms-3">
                    @include('folders.partials.tree', ['folders' => $folder->children])
                </div>
            @endif
        </li>
    @endforeach
</ul>


        
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.folder-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const folderId = this.dataset.folderId;
            window.location.href = `/folders/${folderId}`;
        });
    });
});
</script>