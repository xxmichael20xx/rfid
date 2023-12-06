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
									<img src="{{ asset('images/admin-with-cogwheels.png') }}" alt="user profile">
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
                <a class="app-logo" href="/">
                    <img class="img-fluid" src="{{ asset('images/logo.png') }}" alt="logo">
                </a>

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
                        <li class="nav-item has-submenu">
                            <a
                                class="nav-link submenu-toggle collapsed {{ isRouteActive(['payments.overview', 'payments.list', 'payments.recurring', 'payments.types']) }}"
                                href="#"
                                data-bs-toggle="collapse"
                                data-bs-target="#nav-settings-menu"
                                aria-expanded="false"
                                aria-controls="nav-settings-menu"
                            >
                                <span class="nav-link-text">
                                    <i class="fa fa-cogs"></i> Payments
                                </span>
                                <span class="submenu-arrow">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
                                    </svg>
                                </span>
					        </a>

                            <div id="nav-settings-menu" class="submenu nav-settings-menu {{ isRouteShown(['payments.overview', 'payments.expenses', 'payments.list', 'payments.types']) }}" data-bs-parent="#menu-accordion">
						        <ul class="submenu-list list-unstyled ps-4">
							        <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['payments.expenses']) }}" href="{{ route('payments.expenses') }}">
                                            <i class="fa fa-hand-holding-dollar"></i> Expenses
                                        </a>
                                    </li>
							        <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['payments.list']) }}" href="{{ route('payments.list') }}">
                                            <i class="fa fa-money-bill"></i> List
                                        </a>
                                    </li>
							        <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['payments.types']) }}" href="{{ route('payments.types') }}">
                                            <i class="fa fa-cogs"></i> Types
                                        </a>
                                    </li>
						        </ul>
					        </div>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link {{ isRouteActive(['activities.list', 'activities.create', 'activities.update']) }}"
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
                            <a
                                class="nav-link {{ isRouteActive(['visitor-monitoring.index']) }}"
                                href="{{ route('visitor-monitoring.index') }}">
								<i class="fa fa-walking"></i>
                                <span class="nav-link-text">Visitor Monitoring</span>
                            </a>
                        </li>

                        <li class="nav-item has-submenu">
                            <a
                                class="nav-link submenu-toggle collapsed {{ isRouteActive(['user-management.index']) }}"
                                href="#"
                                data-bs-toggle="collapse"
                                data-bs-target="#nav-user-management"
                                aria-expanded="false"
                                aria-controls="nav-settings-menu"
                            >
                                <span class="nav-link-text">
                                    <i class="fa fa-users"></i> User Management
                                </span>
                                <span class="submenu-arrow">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
                                    </svg>
                                </span>
                            </a>

                            <div id="nav-user-management" class="submenu nav-user-management {{ isRouteShown(['user-management.index']) }}" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled ps-4">
                                    <li class="submenu-item">
                                        <a class="submenu-link text-dark @if(request('type') == 'officers') active @endif" href="{{ route('user-management.index', ['type' => 'officers']) }}">
                                            <i class="fa fa-user-secret"></i> Officers
                                        </a>
                                    </li>
                                    <li class="submenu-item">
                                        <a class="submenu-link text-dark @if(request('type') == 'users') active @endif" href="{{ route('user-management.index', ['type' => 'users']) }}">
                                            <i class="fa fa-user-friends"></i> Users
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item has-submenu">
                            <a
                                class="nav-link submenu-toggle collapsed {{ isRouteActive(['admin.reports.activity']) }}"
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

                            <div id="nav-admin-reports" class="submenu nav-admin-reports {{ isRouteShown(['admin.reports.activity', 'admin.reports.expenses', 'admin.reports.payments']) }}" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled ps-4">
                                    <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['admin.reports.activity']) }}" href="{{ route('admin.reports.activity') }}">
                                            <i class="fa fa-tasks"></i> Activity
                                        </a>
                                    </li>
                                    <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['admin.reports.expenses']) }}" href="{{ route('admin.reports.expenses') }}">
                                            <i class="fa fa-hand-holding-dollar"></i> Expense
                                        </a>
                                    </li>
                                    <li class="submenu-item">
                                        <a class="submenu-link text-dark {{ isRouteActive(['admin.reports.payments']) }}" href="{{ route('admin.reports.payments') }}">
                                            <i class="fa fa-money-bill"></i> Payments
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
                        if (e.redirect) {
                            window.location.href = e.redirect
                        }
                    }

                    // check if event has a reload
                    if (typeof e.reload !== 'undefined') {
                        if (e.reload) {
                            window.location.reload()
                        }
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
