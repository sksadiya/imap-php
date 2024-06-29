@extends('layouts.extend')

@section('content')
<div class="container">
  <div class="row my-5">
    <div class="col-md-3">
      @include('auth.sidebar')
    </div>
    <div class="col-md-9">
      @include('layout.message')
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Page</th>
            <th scope="col">Slug</th>
            <th scope="col">Location</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($menus as $menu)
        <tr>
        <th scope="row">{{ $menu->id }}</th>
        <td>{{ $menu->title }}</td>
        <td>{{ $menu->slug }}</td>
        <td>{{ $menu->location }}</td>
        <td>{{ $menu->status == '1' ? 'Published' : 'Draft' }}</td>
        <td>
          <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-primary btn-sm"><i
            class="fa-regular fa-pen-to-square"></i>
          </a>
          <a href="{{ route('menu.delete', $menu->id) }}" onclick="return confirm('are you sure?')"
          class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
        </td>
        </tr>
      @endforeach
        </tbody>
      </table>
      <form action="{{ route('menu.create') }}" method="post">
        @csrf
        <div class="card border-0 shadow">
          <div class="card-header bg-primary text-white">
            Create menu
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Title"
                  name="title" id="title" />
                @error('title')
                  <p class="invalid-feedback">{{ $message }}</p>
                @enderror
              </div>
              <div class="col-md-4 mb-3">
                <label for="title" class="form-label">Location</label>
                <input type="text" class="form-control @error('location') is-invalid @enderror" placeholder="location"
                  name="location" id="location" />
                @error('location')
                  <p class="invalid-feedback">{{ $message }}</p>
                @enderror
              </div>
              </div>
            <button class="btn btn-primary mt-2">Create</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')

@endsection