<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Stock Management') }}</title>

        <!-- Styles -->
        @include('layouts.partials.styles')
        @yield('custom-styles')
    </head>
    <body>
        <div id="app">
            @include('layouts.partials.sidebar')
            
            <div id="main" class='layout-navbar'>
                @include('layouts.partials.header')
                <div id="main-content">

                    <div class="page-heading">
                        <!-- Alert container -->
                        <div id="alert" class="alert" style="display: none;"></div>
                        @yield('content')
                    </div>

                    {{-- @include('layouts.partials.footer') --}}
                    <footer>
                        <div class="footer clearfix mb-0 text-muted">
                            <div class="float-start">
                                <p>2025 &copy; Grace Technology</p>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        @include('layouts.partials.scripts')

        @yield('custom-scripts')

    </body>
</html>