<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
                        <div class="column is-one-fifth is-hidden-touch">
                            @include('layouts.sidebar')
                        </div>

                        <div class="column">
                            @include('layouts.hero')
                            @yield('content')
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <footer class="footer">
            <div class="content has-text-centered">
                <p>
                    <strong>UiPath Watcher</strong> - v1.0 - 2020</a>
                </p>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
