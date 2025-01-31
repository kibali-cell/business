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
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Root</a></li>
            @foreach($breadcrumbs as $crumb)
                <li class="breadcrumb-item">
                    <a href="{{ route('folders.show', $crumb['id']) }}">{{ $crumb['name'] }}</a>
                </li>
            @endforeach
        </ol>
    </nav>

    <!-- Folder List -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Subfolders</div>
                <div class="card-body">
                    @include('folders.partials.tree', ['folders' => $folder->children])
                </div>
            </div>
        </div>

        <!-- Document List -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span>Documents in {{ $folder->name }}</span>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
                <div class="card-body">
                    @include('documents.partials.list', ['documents' => $documents])
                </div>
            </div>
        </div>
    </div>
</div>

        </div>

    @include('home.script')
  </body>
</html>