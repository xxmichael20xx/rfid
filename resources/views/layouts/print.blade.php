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

    <style>
        .signature .author {
            border-bottom: 1px solid black;
            width: fit-content;
            padding-left: 1em;
            padding-right: 1em;
        }

        table thead {
            background-color: #6ddcac;
        }
    </style>
    @yield('styles')
</head> 

<body class="app pt-0">
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col-12 text-center">
                <img src="{{ asset('images/full_logo.png') }}" style="width: 100px; height: 100px;">
                <h1 class="text-dark m-0">Glen Ville Phase 1 - @yield('title')</h1>
                <h3 class="text-dark m-0">Date: @yield('range')</h3>
            </div>
        </div>

        @yield('content')

        <div class="row mt-3">
            <div class="col-6">
                <p class="text-dark m-0"><b>Report generated:</b> <br>{{ \Carbon\Carbon::now()->format('M d, Y @ h:i A') }}</p>
            </div>

            <div class="col-6 d-flex justify-content-end flex-column align-items-end signature">
                <p class="mb-2 text-dark author">{{ auth()->user()->full_name }}</p>

                @php
                    $role = auth()->user()->role;
                    $role = $role == 'Admin' ? 'President' : $role;
                @endphp
                <p class="text-dark role fw-bold me-4">{{ $role }}</p>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])

    @yield('scripts')

    <script>
        setTimeout(() => {
            window.print()
            window.close()
        }, 1500);
    </script>
</body>
</html>
    