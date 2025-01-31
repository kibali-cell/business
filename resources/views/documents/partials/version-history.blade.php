<div class="card mt-4">
    <div class="card-header">
        <h5>Version History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Version</th>
                        <th>Uploaded By</th>
                        <th>Date</th>
                        <th>Changes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($versions as $version)
                        <tr>
                            <td>{{ $version->version }}</td>
                            <td>{{ $version->uploader->name }}</td>
                            <td>{{ $version->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $version->changes ?? 'No changes recorded' }}</td>
                            <td>
                                <a href="{{ Storage::url($version->path) }}" 
                                   class="btn btn-sm btn-primary"
                                   download>
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>