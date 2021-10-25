<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand mr-3" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown mr-1">
                    <a class="nav-link dropdown-toggle" href="#" id="work-dropdown" role="button" data-toggle="dropdown">
                        Your Work
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Assigned to me</a>
                        <a class="dropdown-item" href="#">Recent Work</a>
                    </div>
                </li>
                <li class="nav-item dropdown mr-1">
                    <a class="nav-link dropdown-toggle" href="#" id="projects-dropdown" role="button" data-toggle="dropdown">
                        Projects
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Recent Project</a>
                        @isset($mostRecentProject)
                            <a href="{{ route('projects.show', ['project' => $mostRecentProject]) }}" class="dropdown-item">
                                <i class="fas fa-chevron-right mr-1"></i>
                                {{ $mostRecentProject->name }}
                            </a>
                            <div class="dropdown-divider"></div>
                        @endisset
                        <a class="dropdown-item" href="{{ route('projects.index') }}">All Projects</a>
                    </div>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createProjectModal">Create Project</button>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <form action="#" method="POST" class="form-inline">
                    @csrf
                    <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="Search" id="search" name="search">
                </form>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link" id="notifications" role="button" data-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-item" id="notification-container">
                            <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile">
                            <p>
                                <span class="font-weight-bold text-capitalize">Admin</span> assigned you
                                <a href="#">Task A</a>
                            </p>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-item" id="notification-container">
                            <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile">
                            <p>
                                <span class="font-weight-bold text-capitalize">Admin</span> assigned you
                                <a href="#">Task A</a>
                            </p>
                        </div>
                    </div>
                </li>
                <!-- Authentication Links -->
                <li class="nav-item dropdown">
                    <a id="user-dropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item">Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>