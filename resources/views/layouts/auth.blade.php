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
</head> 

<body class="app pt-0">
    @yield('content')
</body>
</html> 
    