
<ul class="folder-tree list-unstyled">
    @foreach($folders as $folder)
        <li>
            <div class="folder-item d-flex align-items-center py-1 px-2" 
                 data-folder-id="{{ $folder->id }}"
                 role="button">
                <i class="fas fa-folder me-2 text-warning"></i>
                <span class="folder-name">{{ $folder->name }}</span>
                @if($folder->children->count() > 0)
                    <span class="badge bg-secondary ms-auto">
                        {{ $folder->children->count() }}
                    </span>
                @endif
            </div>
            @if($folder->children->count() > 0)
                <div class="ms-3">
                    @include('folders.partials.tree', ['folders' => $folder->children])
                </div>
            @endif
        </li>
    @endforeach
</ul>

<style>
    .folder-tree {
        margin-left: 1rem;
    }
    .folder-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    .folder-item:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.folder-item').forEach(item => {
        item.addEventListener('click', (e) => {
            const target = e.target.closest('.folder-item');
            if (!target) return;

            const folderId = target.dataset.folderId;
            if (folderId) {
                window.location.href = `/folders/${folderId}`;
            }
        });
    });
});
</script>