<nav class="navbar navbar-expand-lg sticky-top custom-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('feed') }}">
            <span class="brand-badge me-2">
                <i class="bi bi-person-fill"></i>
            </span>
            <span class="brand-title">PPKD<span class="text-accent">JAKPUS</span></span>
        </a>

        <button class="navbar-toggler border-0 p-1 ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-2 text-accent"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('feed') }}">
                        <i class="bi bi-house-door-fill text-accent me-1"></i> Feed
                    </a>
                </li>
                {{-- Geolocation Auto Indicator --}}
                {{-- <li class="nav-item me-lg-2 my-1 my-lg-0 d-none" id="navbar-loc-item">
                    <span class="badge bg-light text-muted border py-1 px-2 d-inline-flex align-items-center" style="font-size: 0.75rem;" title="Lokasi otomatis terdeteksi via Geolocation API">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        <span id="navbar-session-loc-text">Mendeteksi lokasi...</span>
                    </span>
                </li> --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="avatar-sm me-2">
                        @else
                            <span class="avatar-placeholder avatar-sm me-2">
                                <i class="bi bi-person-fill"></i>
                            </span>
                        @endif
                        <span class="fw-semibold">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2 border-bottom border-subtle mb-1">
                            <div class="fw-bold text-truncate" style="max-width: 180px;">{{ Auth::user()->name }}</div>
                            <div class="text-muted small text-truncate" style="max-width: 180px;">{{ Auth::user()->email }}</div>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                <i class="bi bi-person text-accent me-2"></i> Profil Saya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                <i class="bi bi-pencil-square text-accent me-2"></i> Edit Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('password.edit') }}">
                                <i class="bi bi-key text-accent me-2"></i> Ubah Password
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST"
                                  onsubmit="sessionStorage.removeItem('user_location'); return confirm('Apakah Anda yakin ingin keluar dari akun?')">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
