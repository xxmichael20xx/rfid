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

    @yield('styles')
</head>

<body class="app">
    <header class="app-header fixed-top">
        <div class="app-header-inner">
            <div class="container-fluid py-2">
                <div class="app-header-content">
                    <div class="row text-end">
                        <div class="app-utilities">
                            <div class="app-utility-item app-user-dropdown dropdown">
                                <a
                                    class="dropdown-toggle"
                                    id="user-dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    role="button"
                                    aria-expanded="false">
									<img src="{{ asset('images/guard.png') }}" alt="user profile">
                                </a>
                                <ul class="dropdown-menu shadow-lg" aria-labelledby="user-dropdown-toggle">
                                    <li>
                                        <a
                                            href="#!"
                                            class="dropdown-item"
                                            data-bs-toggle="modal"
                                            data-bs-target="#updateAccount"
                                        >
                                            <i class="fa fa-user"></i> Update Account
                                        </a>
									</li>
                                    <li>
										<a
											class="dropdown-item"
											href="{{ route('logout') }}"
											onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
										>
											<i class="fa fa-sign-out"></i> Logout
										</a>

										<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
											@csrf
										</form>
									</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="app-sidepanel" class="app-sidepanel">
            <div id="sidepanel-drop" class="sidepanel-drop"></div>
            <div class="sidepanel-inner d-flex flex-column">
                <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
                <a class="app-logo" href="{{ route('guard.rfid-monitoring.index') }}">
                    <img class="img-fluid" src="{{ asset('images/logo.png') }}" alt="logo">
                </a>

                <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
                    <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                        <li class="nav-item">
                            <a class="nav-link {{ isRouteActive(['guard.dashboard']) }}" href="{{ route('guard.dashboard') }}">
								<i class="fa fa-tachometer-alt"></i>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ isRouteActive(['guard.rfid-monitoring.index']) }}" href="{{ route('guard.rfid-monitoring.index') }}">
								<i class="fa fa-book"></i>
                                <span class="nav-link-text">RFID Monitoring</span>
                            </a>
                        </li>
                        <li class="nav-item has-submenu">
                            <a
                                class="nav-link submenu-toggle collapsed {{ isRouteActive(['guard.visitors.monitoring', 'guard.visitors.list']) }}"
                                href="#"
                                data-bs-toggle="collapse"
                                data-bs-target="#nav-settings-menu"
                                aria-expanded="false"
                                aria-controls="nav-settings-menu"
                            >
                                <span class="nav-link-text">
                                    <i class="fa fa-walking"></i> Visitors
                                </span>
                                <span class="submenu-arrow">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
                                    </svg>
                                </span>
					        </a>

                            <div id="nav-settings-menu" class="submenu nav-settings-menu {{ isRouteShown(['guard.visitors.monitoring', 'guard.visitors.list']) }}" data-bs-parent="#menu-accordion">
						        <ul class="submenu-list list-unstyled ps-4">
							        <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['guard.visitors.monitoring']) }}" href="{{ route('guard.visitors.monitoring') }}">
                                            <i class="fa fa-eye"></i> Monitoring
                                        </a>
                                    </li>
							        <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['guard.visitors.list']) }}" href="{{ route('guard.visitors.list') }}">
                                            <i class="fa fa-list"></i> List
                                        </a>
                                    </li>
						        </ul>
					        </div>
                        </li>

                        <li class="nav-item has-submenu">
                            <a
                                class="nav-link submenu-toggle collapsed {{ isRouteActive(['reports.visitors', 'reports.rfid-monitorings']) }}"
                                href="#"
                                data-bs-toggle="collapse"
                                data-bs-target="#nav-admin-reports"
                                aria-expanded="false"
                                aria-controls="nav-settings-menu"
                            >
                                <span class="nav-link-text">
                                    <i class="fa fa-file-export"></i> Reports
                                </span>
                                <span class="submenu-arrow">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
                                    </svg>
                                </span>
                            </a>

                            <div id="nav-admin-reports" class="submenu nav-admin-reports {{ isRouteShown(['reports.visitors', 'reports.rfid-monitorings']) }}" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled ps-4">
                                    <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['reports.visitors']) }}" href="{{ route('reports.visitors') }}">
                                            <i class="fa fa-walking"></i> Visitors
                                        </a>
                                    </li>
                                    <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['reports.rfid-monitorings']) }}" href="{{ route('reports.rfid-monitorings') }}">
                                            <i class="fa fa-id-card"></i> RFID
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </header>
    
    @livewire('update-account')

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
    
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
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
                    item.dispatchEvent(new new Event('change', {
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
