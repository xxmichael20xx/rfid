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

<body class="app pt-0">
    @yield('content')

    @vite(['resources/js/app.js'])
    @livewireScripts

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
    