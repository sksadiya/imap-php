@extends('layouts.extend')

@section('content')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('auth.sidebar')
        </div>
        <div class="col-md-9">
            @include('layout.message')
            <form action="{{ route('menu.update', $menu->id) }}" method="post">
                @csrf
                <div class="card border-0 shadow">
                    <div class="card-header bg-primary text-white">
                        Edit Menu
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" value="{{ $menu->title }}"
                                    class="form-control @error('title') is-invalid @enderror" placeholder="Title"
                                    name="title" id="title" />
                                @error('title')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="location" class="form-label">location</label>
                                <input type="text" value="{{ $menu->location }}"
                                    class="form-control @error('location') is-invalid @enderror" placeholder="location"
                                    name="location" id="location" />
                                @error('location')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            
                        </div>
                        
                        <button class="btn btn-primary mt-2">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection