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
<div class="container my-4">
  <h1 class="mb-4">Suppliers</h1>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">
    <i class="mdi mdi-plus-circle me-2"></i> Add New Supplier
  </a>

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($suppliers as $supplier)
          <tr>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->email }}</td>
            <td>{{ $supplier->phone }}</td>
            <td>{{ $supplier->address }}</td>
            <td>
              <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a>
              <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning"><i class="mdi mdi-pencil"></i></a>
              <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {!! $suppliers->links() !!}
</div>
