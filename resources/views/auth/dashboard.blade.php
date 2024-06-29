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
            <th scope="col">Status</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pages as $page)
        <tr>
        <th scope="row">{{ $page->id }}</th>
        <td>{{ $page->title }}</td>
        <td>{{ $page->slug }}</td>
        <td>{{ $page->status == '1' ? 'Published' : 'Draft' }}</td>
        <td>
          <a href="{{ route('page.edit', $page->id) }}" class="btn btn-primary btn-sm"><i
            class="fa-regular fa-pen-to-square"></i>
          </a>
          <a href="{{ route('page.delete', $page->id) }}" onclick="return confirm('are you sure?')"
          class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
        </td>
        </tr>
      @endforeach
        </tbody>
      </table>
      <form action="{{ route('page.create') }}" method="post">
        @csrf
        <div class="card border-0 shadow">
          <div class="card-header bg-primary text-white">
            Create Page
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
                <label for="parent_page" class="form-label">Parent Page</label>
                <select name="parent_page" id="parent_page"
                  class="form-control @error('parent_page') is-invalid @enderror">
                  @foreach ($pages as $page)
            <option value="{{ $page->id }}">{{ $page->title }}</option>
          @endforeach
                </select>
                @error('parent_page')
          <p class="invalid-feedback">{{ $message }}</p>
        @enderror
              </div>
              <div class="col-md-4 mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                  <option value="1">Show</option>
                  <option value="0">Hide</option>
                </select>
                @error('status')
          <p class="invalid-feedback">{{ $message }}</p>
        @enderror
              </div>
            </div>
            <div class="col-md-12 mb-3">
              <label for="content" class="form-label">Content</label>
              <textarea class="form-control summernote @error('content') is-invalid @enderror" name="content"
                id="content"></textarea>
              @error('content')
          <p class="invalid-feedback">{{ $message }}</p>
        @enderror
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