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
    </style>
    @yield('styles')
</head> 

<body class="app pt-0">
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="text-dark m-0">Glen Ville II Phase 1 - @yield('title')</h1>
                <h3 class="text-dark m-0">Date: @yield('range')</h3>
            </div>
        </div>

        @yield('content')

        <div class="row mt-3">
            <div class="col-12">
                <p class="text-dark m-0">Report generated: <br>{{ \Carbon\Carbon::now()->format('M d, Y @ h:i A') }}</p>
            </div>
        </div>

        <div class="row mt-5 pt-5 signature">
            <div class="col-9"></div>
            <div class="col-3 d-flex justify-content-center flex-column align-items-center">
                <p class="mb-2 text-dark author">{{ auth()->user()->full_name }}</p>

                @php
                    $role = auth()->user()->role;
                    $role = $role == 'Admin' ? 'President' : $role;
                @endphp
                <p class="text-dark role fw-bold">{{ $role }}</p>
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
    