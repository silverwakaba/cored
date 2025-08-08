<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav"><!-- Right -->
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        @guest
            <li class="nav-item">
                <a class="nav-link" href="#">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Login</a>
            </li>
        @endguest
    </ul>
    <ul class="navbar-nav ml-auto"><!-- Left -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        @auth
            <li class="nav-item">
                <a href="javascript:void(0);" onclick="document.getElementById('myLogoutForm').submit();" class="nav-link">Logout</a>
            </li>
        @endauth
    </ul>
    @auth
        <form id="myLogoutForm" action="{{ route('fe.auth.logout') }}" method="POST" class="d-none">
            <input type="hidden" name="_token" class="d-none" value="{{ csrf_token() }}" readonly />
        </form>
    @endauth
</nav>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/" class="brand-link">
        <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="{{ config('app.name', 'Cored') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'Cored') }}</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">ABC</a>
            </div>
        </div>
        <nav class="mt-2">
            <x-Adminlte.Dynamic-Menu />
        </nav>
    </div>
</aside>