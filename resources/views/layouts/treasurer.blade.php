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
                            <div class="app-utility-item app-user-dropdown dropdown">
                                <a
                                    class="dropdown-toggle"
                                    id="user-dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    role="button"
                                    aria-expanded="false">
									<img src="{{ asset('images/accountant.png') }}" alt="user profile">
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
                <a class="app-logo" href="{{ route('payments.expenses') }}">
                    <img class="img-fluid" src="{{ asset('images/logo.png') }}" alt="logo">
                </a>

                <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
                    <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                        <li class="nav-item">
                            <a class="nav-link {{ isRouteActive(['payments.expenses']) }}" href="{{ route('payments.expenses') }}">
                                <i class="fa fa-hand-holding-dollar"></i> Expenses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ isRouteActive(['payments.list']) }}" href="{{ route('payments.list') }}">
                                <i class="fa fa-money-bill"></i> List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ isRouteActive(['payments.types']) }}" href="{{ route('payments.types') }}">
                                <i class="fa fa-cogs"></i> Types
                            </a>
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
