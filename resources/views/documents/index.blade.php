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
    <div class="row">
        <!-- Folder Tree -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Folders</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newFolderModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    @include('documents.partials.folder-tree', ['folders' => $folders])
                </div>
            </div>
        </div>

        <!-- Document List -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Documents</h5>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-upload me-2"></i>Upload Document
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Current Version</th>
                                    <th>Folder</th>
                                    <th>Uploaded By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                <tr>
                                    <td>{{ $document->title }}</td>
                                    <td>{{ $document->currentVersion->version }}</td>
                                    <td>{{ $document->folder->name ?? 'Root' }}</td>
                                    <td>{{ $document->currentVersion->uploader->name }}</td>
                                    <td>
                                        <a href="{{ route('documents.show', $document) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

        </div>

        @include('documents.modals.upload')
@include('documents.modals.new-folder')

    @include('home.script')
  </body>
</html>