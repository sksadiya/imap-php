@extends('layouts.auth')
@section('title')
   Login
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid align-items-center justify-content-center p-4">
        <div class="col-12 text-center">
            <a href="{{ url('/') }}" class="image mb-7 mb-sm-10 image-medium">
                <img alt="Logo" src="{{ asset('assets/images/infyom.png') }}" class="img-fluid object-contain">
            </a>
        </div>
        <div class="width-540">
          @include('layouts.message')
        </div>
        <div class="bg-white rounded-15 shadow-md width-540 px-5 px-sm-7 py-10 mx-auto">
            <h1 class="text-center mb-7">Sign In</h1>
            <form method="POST" action="{{ route('admin.authenticate') }}">
                @csrf
                <div class="mb-sm-7 mb-4">
                    <label for="email" class="form-label">
                        Email<span class="required"></span>
                    </label>
                    <input name="email" type="email" class="form-control" autofocus id="email"
                        aria-describedby="emailHelp" required placeholder="Email">
                </div>
                <div class="mb-sm-7 mb-4">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label">Password<span
                                class="required"></span></label>
                    </div>
                    <div class="mb-3 position-relative">
                        <input name="password" type="password" class="form-control" id="password" required
                            placeholder="Password" aria-label="Password" data-toggle="password">
                        <span
                            class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600">
                            <i class="bi bi-eye-slash-fill"></i>
                        </span>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
