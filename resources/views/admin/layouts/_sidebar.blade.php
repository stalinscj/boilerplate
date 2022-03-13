<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('admin.dashboard') }}" class="brand-link">
    <img src="{{ asset('img/logo.png') }}"
      alt="Logo"
      class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('img/user.png') }}" class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <hr>

        {{-- Logout --}}
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-power-off"></i>
            <p>Cerrar Sesión</p>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </li>
        {{-- ./Logout --}}

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
