<!-- resources/views/documents/show.blade.php -->
<div class="card">
    <div class="card-header">
        <h4>{{ $document->title }}</h4>
    </div>
    <div class="card-body">
        <div id="pdf-viewer" style="height: 600px;"></div>
        
        @if(pathinfo($document->currentVersion->path, PATHINFO_EXTENSION) !== 'pdf')
        <div class="alert alert-info">
            File preview not available. <a href="{{ route('documents.download', $document) }}">Download</a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/pdf.js') }}"></script>
<script>
    const url = "{{ Storage::url($document->currentVersion->path) }}";
    
    pdfjsLib.getDocument(url).promise.then(pdf => {
        pdf.getPage(1).then(page => {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            
            const viewport = page.getViewport({ scale: 1.5 });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            document.getElementById('pdf-viewer').appendChild(canvas);

            page.render({
                canvasContext: context,
                viewport: viewport
            });
        });
    });
</script>
@endpush