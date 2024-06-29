<div class="card border-0 shadow-lg">
  <div class="card-header bg-primary text-white">
  Welcome, {{ Auth::guard('admin')->user()->name }}
  </div>
  <div class="card-body">
    <div class="h5 text-center">
      <strong>{{ Auth::guard('admin')->user()->name }}</strong>
    </div>
  </div>
</div>
<div class="card border-0 shadow-lg mt-3">
  <div class="card-header bg-primary text-white">
    Navigation
  </div>
  <div class="card-body sidebar">
    <ul class="nav flex-column">
      <li class="nav-item">
      <a class="nav-link text-dark" href="{{ route('admin.dashboard')}}">Pages</a>
      </li>
      <li class="nav-item">
      <a class="nav-link" href="{{ route('menu.list')}}">Menus</a>
      </li>
      <li class="nav-item">
      <a class="nav-link" href="{{ route('menu.setting')}}">Menu Settings</a>
      </li>
    </ul>
  </div>
</div>
