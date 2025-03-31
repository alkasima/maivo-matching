<div id="sidebar-wrapper">
    <div class="sidebar-heading d-flex align-items-center">
        <div class="text-white fw-bold fs-4">
            MAIVO<span class="ms-1"><i class="bi bi-send-fill" style="transform: rotate(45deg);"></i></span>
        </div>
    </div>
    <div class="list-group list-group-flush">
        <nav class="nav flex-column">
            <a class="nav-link @if(request()->routeIs('owner.dashboard')) active @endif" href="{{ route('owner.dashboard') }}">
                <span class="nav-icon"><i class="bi bi-grid"></i></span>
                Dashboard
            </a>
            <a class="nav-link" href="{{ route('marketplace.index') }}">
                <span class="nav-icon"><i class="bi bi-shop"></i></span>
                Marketplace
            </a>
            <a class="nav-link" href="{{ route('job.my-jobs') }}">
                <span class="nav-icon"><i class="bi bi-briefcase"></i></span>
                Jobs
            </a>
            <a class="nav-link" href="#">
                <span class="nav-icon"><i class="bi bi-clock-history"></i></span>
                History
            </a>
        </nav>
    </div>
    <div id="sidebar-footer">
        <!-- <div class="profile-photo-area">
            <img src="{{ asset('images/avatar.png') }}" alt="Profile Photo">
            <p>User Name</p>
        </div> -->
        <a class="nav-link" href="{{ route('owner.settings') }}">
            <span class="nav-icon"><i class="bi bi-gear"></i></span>
            Settings
        </a>
    </div>
</div>
