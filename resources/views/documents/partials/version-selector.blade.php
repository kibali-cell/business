<div class="version-selector mb-4">
    <label>Document Version:</label>
    <select class="form-select" onchange="window.location.href=this.value">
        @foreach($document->versions as $version)
            <option value="{{ route('documents.show', ['document' => $document, 'version' => $version->id]) }}"
                {{ $version->id === $document->currentVersion->id ? 'selected' : '' }}>
                Version {{ $version->version }} ({{ $version->created_at->format('M d, Y') }})
            </option>
        @endforeach
    </select>
</div>