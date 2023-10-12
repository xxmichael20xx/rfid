<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name', 'RFID Portal') }}</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico">

    {{-- Styles --}}
    @vite(['resources/sass/app.scss'])
    @livewireStyles
</head>

<body class="app">
    <header class="app-header fixed-top">
        <div class="app-header-inner">
            <div class="container-fluid py-2">
                <div class="app-header-content">
                    <div class="row text-end">
                        <div class="app-utilities">
                            <!--//app-utility-item-->
                            <div class="app-utility-item">
                                <a href="#" title="Settings">
									<i class="fa fa-cogs"></i>
                                </a>
                            </div>
                            <!--//app-utility-item-->

                            <div class="app-utility-item app-user-dropdown dropdown">
                                <a
                                    class="dropdown-toggle"
                                    id="user-dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    role="button"
                                    aria-expanded="false">
									<img src="{{ asset('images/profile_avatar.png') }}" alt="user profile">
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="user-dropdown-toggle">
                                    <li><a class="dropdown-item" href="#">Account</a></li>
                                    <li><a class="dropdown-item" href="#">Settings</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
										<a
											class="dropdown-item"
											href="{{ route('logout') }}"
											onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
										>
											{{ __('Logout') }}
										</a>

										<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
											@csrf
										</form>
									</li>
                                </ul>
                            </div>
                            <!--//app-user-dropdown-->
                        </div>
                        <!--//app-utilities-->
                    </div>
                    <!--//row-->
                </div>
                <!--//app-header-content-->
            </div>
        </div>
        <div id="app-sidepanel" class="app-sidepanel">
            <div id="sidepanel-drop" class="sidepanel-drop"></div>
            <div class="sidepanel-inner d-flex flex-column">
                <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
                <div class="app-branding">
                    <a class="app-logo" href="/">
						{{-- <img class="logo-icon me-2" src="assets/images/app-logo.svg" alt="logo"> --}}
						<span class="logo-text">{{ config('app.name', 'RFID Portal') }}</span>
					</a>
                </div>

                <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
                    <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                        <li class="nav-item">
                            <a class="nav-link {{ isRouteActive(['dashboard']) }}" href="{{ route('dashboard') }}">
								<i class="fa fa-tachometer-alt"></i>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link {{ isRouteActive(['homeowners.list', 'homeowners.create', 'homeowners.update', 'homeowners.view']) }}"
                                href="{{ route('homeowners.list') }}">
								<i class="fa fa-house"></i>
                                <span class="nav-link-text">Home Owners</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link {{ isRouteActive(['block-management.list']) }}"
                                href="{{ route('block-management.list') }}">
								<i class="fa fa-box"></i>
                                <span class="nav-link-text">Blocks Managements</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link {{ isRouteActive(['profiles.list']) }}"
                                href="{{ route('profiles.list') }}">
								<i class="fa fa-users"></i>
                                <span class="nav-link-text">Profiles</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ isRouteActive(['payments.list']) }}" href="{{ route('payments.list') }}">
								<i class="fa fa-dollar"></i>
                                <span class="nav-link-text">Payments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link {{ isRouteActive(['activities.list', 'activities.create']) }}"
                                href="{{ route('activities.list') }}">
								<i class="fa fa-tasks"></i>
                                <span class="nav-link-text">Activities</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a 
                                class="nav-link {{ isRouteActive(['rfid.list']) }}"
                                href="{{ route('rfid.list') }}">
								<i class="fa fa-id-card"></i>
                                <span class="nav-link-text">RFID Panel</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link on-dev" href="#">
								<i class="fa fa-qrcode"></i>
                                <span class="nav-link-text">QR Panel</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link on-dev" href="#">
								<i class="fa fa-walking"></i>
                                <span class="nav-link-text">Visitor Monitoring</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ isRouteActive(['user-management.index']) }}" href="{{ route('user-management.index') }}">
								<i class="fa fa-users"></i>
                                <span class="nav-link-text">User Management</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </header>

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container mx-auto">
                @yield('flashMessage')
                @yield('content')
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    @livewireScripts
    @yield('scripts')

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            Livewire.on('show.dialog', (e) => {
                Swal.fire({
                    icon: e.icon,
                    title: e.title,
                    text: e.message
                }).then(() => {
                    // check if the event has a redirect
                    if (typeof e.redirect !== 'undefined') {
                        window.location.href = e.redirect
                    }

                    // check if event has a reload
                    if (typeof e.reload !== 'undefined') {
                        window.location.reload()
                    }
                })
            })

            const onDev = document.querySelectorAll('.on-dev')
            if (onDev.length > 0) {
                onDev.forEach((item) => {
                    item.addEventListener('click', () => {
                        Swal.fire({
                            icon: 'info',
                            title: 'On Development',
                            text: 'This is currently on development'
                        })
                    })
                })
            }

            /** Initialize Bootstrap 5 Tooltips */
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

            /** Trigger change on class 'load-change' */
            const loadChange = document.querySelectorAll('.load-change')
            if (loadChange.length > 0) {
                loadChange.forEach((item) => {
                    item.dispatchEvent(new Event('change', {
                        bubbles: true, // Allow the event to bubble up the DOM
                        cancelable: true // Allow the event to be canceled
                    }))
                })
            }

            /** Initialize Livewire event listener - show profile update modal */
            Livewire.on('show.profile-update', () => {
                const updateProfileModal = new bootstrap.Modal('#updateProfileModal', {})
                updateProfileModal.show()
            })
        })
    </script>
</body>
</html>
