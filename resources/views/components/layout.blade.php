<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name') }}</title>

        <!-- Icon -->
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        <!-- Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">


        <!-- Styles -->
        @vite('resources/css/app.css')
        @stack('styles')

        {{-- =======================================================
        * Template Name: NiceAdmin - v2.2.2
        * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
        * Author: BootstrapMade.com
        * License: https://bootstrapmade.com/license/
        ======================================================== --}}

    </head>
    <body class="antialiased">
        {{ $slot }}
    </body>

    <!-- Javasript -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="{{ asset('build/assets/apexcharts.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('build/assets/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('build/assets/chart.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('build/assets/echarts.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('build/assets/quill.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('build/assets/simple-datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('build/assets/tinymce.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('build/assets/validate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('build/assets/main.js') }}" type="text/javascript"></script>
    @stack('scripts')

</html>
