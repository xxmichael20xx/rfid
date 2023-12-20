<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>{{ config('app.name', 'RFID Portal') }}</title>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico"> 

    <style>
        .report-signature .author {
            margin: 0;
            padding: 0;
            margin-bottom: 10px;
        }

        .app {
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 1280px;
            margin: auto;
        }

        .flex {
            display: flex;
        }

        .flex.flex-column {
            flex-direction: column;
        }

        .flex-column.flex-center {
            align-items: center;
            justify-content: center;
        }

        .flex-column.flex-end {
            align-items: end;
        }

        h1, h3 {
            margin: 0;
            padding: 0;
        }

        .report-generated,
        .report-signature {
            font-size: 1em;
            padding: 0 0 0 1em;
            margin: 0;
        }

        .report-signature {
            margin-top: 2.5em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: #6ddcac;
        }

        thead th {
            padding: 10px;
            text-align: left;
        }

        table tbody tr:first-child td {
            padding-top: 1em;
        }

        table tbody tr td {
            padding: 5px;
            border-bottom: 1px solid gray;
        }

        .head-title {
            margin-bottom: 2em;
        }

        .report-footer {
            margin-top: 1em;
            display: flex;
        }
    </style>
</head> 

<body class="app">
    <div class="container">
        <div class="flex flex-column flex-center head-title">
            <img src="{{ asset('images/full_logo.png') }}" style="width: 150;x height: 150px;">
            <h1 class="text-dark m-0">Glen Ville Phase 1 - @yield('title')</h1>
            <h3 class="text-dark m-0">Date: @yield('range')</h3>
        </div>

        @yield('content')

        <div class="report-footer">
            <p class="report-generated">
                <b>Report generated:</b>
                <span style="display: block; margin-top: 10px;">{{ \Carbon\Carbon::now()->format('M d, Y @ h:i A') }}</span>    
            </p>

            <div class="report-signature">
                <div class="flex flex-column">
                    <p class="author">{{ auth()->user()->full_name }}</p>
    
                    @php
                        $role = auth()->user()->role;
                        $role = $role == 'Admin' ? 'President' : $role;
                    @endphp
                    <p style="margin: 0; padding: 0;"><b>{{ $role }}</b></p>
                </div>
            </div>
        </div>
    </div>

    @yield('scripts')

    <script>
        setTimeout(() => {
            // window.print()
            // window.close()
        }, 1500);
    </script>
</body>
</html>
    