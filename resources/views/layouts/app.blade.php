<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} {{ config('app.version', ' - Demo Version') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        @yield('assets')
    </head>
    <body>
        <div id="app">
            @include('layouts.menu')

            @if (Auth::guest())
                @yield('content')
            @else
                <div class="container">
                    <div class="columns">
                        <div class="column is-3">
                            @include('layouts.sidebar')
                        </div>

                        <div class="column is-9">
                            @include('layouts.hero')
                            @yield('content')
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
