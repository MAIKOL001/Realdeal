<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet" />

    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        .form-control:focus {
            box-shadow: none;
        }
    </style>

    {{-- - Page Styles - --}}
    @stack('page-styles')
    @livewireStyles
</head>

<body>
    <script src="{{ asset('dist/js/demo-theme.min.js') }}"></script>

    <div class="page">
        <header class="navbar navbar-expand-md d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ url('/') }}">
                       <h1>Real Deal logistics</h1>
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="d-none d-md-flex">

                        
                            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
                               data-bs-placement="bottom">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                            </a>
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
                               data-bs-placement="bottom">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                            </a>
                            

                        <div class="nav-item dropdown d-none d-md-flex me-3">
                            <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                aria-label="Show notifications">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                    <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                </svg>

                                @if (auth()->user()->unreadNotifications->count() !== 0)
                                    <span class="badge bg-red"></span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">

                                {{--                                    <div class="card"> --}}
                                {{--                                        <div class="card-header"> --}}
                                {{--                                            <h3 class="card-title">Last updates</h3> --}}
                                {{--                                        </div> --}}
                                {{--                                        <div class="list-group list-group-flush list-group-hoverable"> --}}

                                {{--                                            @foreach (auth()->user()->unreadNotifications as $notification) --}}
                                {{--                                                <a href="#" class="text-success"> --}}
                                {{--                                                    <li class="p-1 text-success"> {{$notification->data['data']}}</li> --}}
                                {{--                                                </a> --}}
                                {{--                                                <div class="list-group-item"> --}}
                                {{--                                                    <div class="row align-items-center"> --}}
                                {{--                                                        <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div> --}}
                                {{--                                                        <div class="col text-truncate"> --}}
                                {{--                                                            <a href="#" class="text-body d-block">Example 1</a> --}}
                                {{--                                                            <div class="d-block text-muted text-truncate mt-n1"> --}}
                                {{--                                                                Change deprecated html tags to text decoration classes (#29604) --}}
                                {{--                                                            </div> --}}
                                {{--                                                        </div> --}}
                                {{--                                                        <div class="col-auto"> --}}
                                {{--                                                            <a href="#" class="list-group-item-actions"> --}}
                                {{--                                                                <!-- Download SVG icon from http://tabler-icons.io/i/star --> --}}
                                {{--                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg> --}}
                                {{--                                                            </a> --}}
                                {{--                                                        </div> --}}
                                {{--                                                    </div> --}}
                                {{--                                                </div> --}}
                                {{--                                            @endforeach --}}
                                {{--                                        </div> --}}
                                {{--                                    </div> --}}
                                <span class="dropdown-header">Dropdown header</span>
                                <a class="dropdown-item" href="#">
                                    Action
                                </a>
                                <a class="dropdown-item" href="#">
                                    Another action
                                </a>
                            </div>
                        </div>

                        {{-- -
                            <div class="dropdown">
                                <a href="#" class="btn dropdown-toggle" data-bs-toggle="dropdown">Open dropdown</a>
                                <div class="dropdown-menu">
                                    <span class="dropdown-header">Dropdown header</span>
                                    <a class="dropdown-item" href="#">
                                        Action
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        Another action
                                    </a>
                                </div>
                            </div>
                            - --}}

                    </div>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm shadow-none"
                                style="background-image: url({{ Auth::user()->photo ? asset('storage/profile/' . Auth::user()->photo) : asset('assets/img/illustrations/profiles/admin.jpg') }})">
                            </span>

                            <div class="d-none d-xl-block ps-2">
                                <div>{{ Auth::user()->name }}</div>
                                {{--                                    <div class="mt-1 small text-muted">UI Designer</div> --}}
                            </div>
                        </a>
                        <div class="dropdown-menu">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon dropdown-item-icon icon-tabler icon-tabler-settings" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                    </path>
                                    <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                </svg>
                                Account
                            </a>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon dropdown-item-icon icon-tabler icon-tabler-logout" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                        <path d="M9 12h12l-3 -3" />
                                        <path d="M18 15l3 -3" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- -
                        <div class="dropdown">
                            <a href="#" class="btn dropdown-toggle" data-bs-toggle="dropdown">Open dropdown</a>
                            <div class="dropdown-menu">

                                <a class="dropdown-item" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
                                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                    </svg>
                                    Action
                                </a>
                                <a class="dropdown-item" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4"></path>
                                        <path d="M13.5 6.5l4 4"></path>
                                    </svg>
                                    Another action
                                </a>
                            </div>
                        </div>
                        - --}}


                </div>
            </div>
        </header>

        <header class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar">
                    <div class="container-xl">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ request()->is('dashboard*') ? 'active' : null }}">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <span
                                        class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Dashboard') }}
                                    </span>
                                </a>
                            </li>


                            <li class="nav-item {{ request()->is('products*') ? 'active' : null }}">
                                <a class="nav-link" href="{{ route('products.index') }}">
                                    <span
                                        class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-packages" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
                                            <path d="M2 13.5v5.5l5 3" />
                                            <path d="M7 16.545l5 -3.03" />
                                            <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
                                            <path d="M12 19l5 3" />
                                            <path d="M17 16.5l5 -3" />
                                            <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5" />
                                            <path d="M7 5.03v5.455" />
                                            <path d="M12 8l5 -3" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Products') }}
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('dispatch*') ? 'active' : null }}">
                                <a class="nav-link" href="{{ route('dispatch.index') }}">
                                    <span
                                        class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg  class="icon icon-tabler icon-tabler-packages" width="24"
                                        height="24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20 14h-2.722L11 20.278a5.511 5.511 0 0 1-.9.722H20a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1ZM9 3H4a1 1 0 0 0-1 1v13.5a3.5 3.5 0 1 0 7 0V4a1 1 0 0 0-1-1ZM6.5 18.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM19.132 7.9 15.6 4.368a1 1 0 0 0-1.414 0L12 6.55v9.9l7.132-7.132a1 1 0 0 0 0-1.418Z"/>
                                          </svg>
                                          
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Dipatch') }}
                                    </span>
                                </a>
                            </li>


                            <li class="nav-item dropdown {{ request()->is('orders*') ? 'active' : null }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        {{-- <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-package-export" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 21l-8 -4.5v-9l8 -4.5l8 4.5v4.5" />
                                            <path d="M12 12l8 -4.5" />
                                            <path d="M12 12v9" />
                                            <path d="M12 12l-8 -4.5" />
                                            <path d="M15 18h7" />
                                            <path d="M19 15l3 3l-3 3" />
                                        </svg> --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 48 48">
                                            <path fill="#38b47b" d="M28.785,2.607c-3.644,0-9.626-0.039-13.415-0.05c-1.909-0.006-4.246-0.443-5.79,0.676 C8.238,4.205,8.046,4.691,7.812,6.328c-0.234,1.638-0.288,4.939-0.181,6.59c0.259,4.026,0.067,8.067-0.146,12.096 c-0.107,2.028-0.22,4.058-0.172,6.088c0.095,4.032,0.823,7.319,1.549,11.287c0.145,0.793,0.334,1.661,0.974,2.153 c0.552,0.424,1.3,0.459,1.996,0.476c5.264,0.131,10.527,0.262,15.791,0.394c3.15,0.079,6.337,0.154,9.412-0.533 c0.495-0.11,0.999-0.247,1.403-0.553c0.657-0.498,0.929-1.347,1.088-2.155c0.354-1.793,0.336-3.635,0.317-5.461 c-0.079-7.545-0.157-14.391-0.236-21.936c-0.007-0.647-0.018-1.317-0.293-1.904c-0.31-0.664-0.915-1.134-1.489-1.591 C34.503,8.64,31.409,5.939,28.785,2.607z"></path><path fill="#2b8c5e" d="M28.565,2.747c0.081,3.28,0.197,6.42,0.278,9.701c0.006,0.235,0.019,0.49,0.169,0.667 c0.168,0.198,0.45,0.236,0.706,0.258c3.173,0.268,6.379,0.136,9.521-0.391L28.565,2.747z"></path><path d="M30.629,45.963c-1.013,0-2.021-0.025-3.02-0.05l-15.79-0.394c-0.669-0.017-1.585-0.04-2.288-0.58 c-0.827-0.636-1.028-1.733-1.162-2.46l-0.311-1.672c-0.624-3.327-1.163-6.2-1.246-9.693c-0.046-1.969,0.059-3.959,0.16-5.884 l0.013-0.242c0.228-4.292,0.396-8.16,0.146-12.038C7.021,11.228,7.077,7.93,7.316,6.257c0.256-1.784,0.514-2.374,1.971-3.43 c1.357-0.983,3.21-0.893,4.842-0.813c0.426,0.021,0.844,0.042,1.243,0.043c1.495,0.004,3.331,0.013,5.231,0.022 c2.914,0.014,5.977,0.028,8.182,0.028c0.153,0,0.298,0.07,0.393,0.19c2.724,3.458,5.915,6.173,8.958,8.592 c0.618,0.491,1.276,1.014,1.631,1.77c0.325,0.695,0.333,1.447,0.34,2.111l0.235,21.936c0.02,1.823,0.04,3.71-0.326,5.563 c-0.16,0.814-0.45,1.83-1.275,2.457c-0.496,0.374-1.084,0.527-1.597,0.643C34.995,45.848,32.802,45.963,30.629,45.963z M12.698,2.968c-1.065,0-2.073,0.125-2.825,0.67c-1.21,0.877-1.346,1.222-1.566,2.762c-0.228,1.587-0.282,4.847-0.177,6.486 c0.253,3.933,0.083,7.832-0.146,12.155l-0.013,0.242c-0.1,1.906-0.204,3.877-0.158,5.808c0.081,3.412,0.613,6.248,1.229,9.532 L9.354,42.3c0.14,0.762,0.305,1.476,0.787,1.847c0.411,0.315,1.015,0.355,1.704,0.373l15.79,0.394 c3.079,0.075,6.262,0.155,9.29-0.521c0.411-0.092,0.877-0.21,1.211-0.463c0.557-0.423,0.772-1.213,0.899-1.854 c0.346-1.752,0.325-3.586,0.307-5.359L39.106,14.78c-0.006-0.589-0.013-1.198-0.246-1.697c-0.254-0.542-0.791-0.969-1.31-1.381 c-3.071-2.441-6.255-5.147-9.007-8.595c-2.186-0.001-5.135-0.016-7.945-0.028c-1.899-0.01-3.735-0.019-5.229-0.022 c-0.414-0.001-0.848-0.022-1.289-0.044C13.619,2.99,13.153,2.968,12.698,2.968z"></path><path d="M32.925,14.009c-1.084,0-2.168-0.046-3.249-0.138c-0.256-0.021-0.732-0.062-1.046-0.434 c-0.271-0.319-0.281-0.73-0.287-0.977c-0.04-1.619-0.089-3.204-0.137-4.789c-0.05-1.626-0.1-3.251-0.141-4.913 c-0.007-0.275,0.212-0.505,0.488-0.512c0.288,0.02,0.505,0.212,0.512,0.488c0.04,1.658,0.09,3.282,0.14,4.906 c0.049,1.586,0.098,3.173,0.138,4.794c0.002,0.084,0.008,0.306,0.051,0.356c0.031,0.037,0.154,0.065,0.366,0.083 c3.131,0.266,6.293,0.135,9.396-0.386c0.27-0.047,0.53,0.138,0.576,0.41s-0.138,0.53-0.41,0.576 C37.207,13.83,35.066,14.009,32.925,14.009z"></path><path fill="#d6e5e5" d="M14.656,18.92c6.147,0.009,12.454-0.027,18.602-0.18c0.554-0.014,1.011,0.425,1.023,0.978 c0.093,4.246,0.117,8.494,0.071,12.741c-0.006,0.525-0.416,0.966-0.94,1c-6.212,0.399-12.638-0.142-18.874-0.229 c-0.572-0.008-1.025-0.492-0.98-1.063c0.179-2.3-0.031-4.234-0.193-6.675c-0.127-1.913,0.066-3.801,0.302-5.698 C13.729,19.294,14.152,18.919,14.656,18.92z M17.076,22.249c-0.046,0.709-0.056,1.513,0.063,2.213 c1.72,0.044,3.44,0.089,5.16,0.133c0-0.746,0-1.491,0-2.237C20.574,22.292,18.801,22.315,17.076,22.249z M25.685,22.285 c0.225,0.825,0.259,1.641,0.18,2.493c1.538,0.003,3.076,0.005,4.614,0.008c0.12,0,0.255-0.006,0.334-0.097 c0.061-0.071,0.069-0.172,0.073-0.265c0.036-0.792,0.073-1.583,0.109-2.375c-1.314-0.004-2.628-0.008-3.941-0.012 C26.591,22.036,26.048,21.998,25.685,22.285z M30.884,27.96c-0.066-0.094-0.2-0.11-0.318-0.117 c-1.539-0.092-3.296-0.195-4.838-0.183c0.003,0.667,0.006,2.185,0.01,2.853c1.617-0.021,3.234-0.042,4.85-0.063 c0.06-0.001,0.126-0.004,0.17-0.043c0.039-0.036,0.049-0.092,0.056-0.144c0.073-0.527,0.111-1.558,0.114-2.089 C30.928,28.1,30.927,28.021,30.884,27.96z M16.853,27.816c-0.175,0.586-0.061,1.715-0.092,2.572 c1.813-0.044,3.627-0.088,5.44-0.131c-0.001-0.766-0.001-1.532-0.002-2.299C20.449,27.715,18.592,27.505,16.853,27.816z"></path><path d="M28.458,34.104c-2.619,0-5.251-0.098-7.829-0.193c-2.007-0.074-4.082-0.151-6.098-0.18 c-0.413-0.006-0.811-0.184-1.088-0.488c-0.279-0.306-0.416-0.701-0.384-1.112c0.146-1.875,0.028-3.511-0.109-5.404l-0.084-1.2 c-0.133-2.007,0.077-3.962,0.305-5.793c0.092-0.748,0.729-1.313,1.483-1.313c0.001,0,0.002,0,0.003,0 c7.306,0.011,13.225-0.047,18.588-0.18c0.402,0.014,0.783,0.138,1.072,0.414c0.291,0.277,0.455,0.651,0.464,1.054 c0.093,4.222,0.117,8.514,0.071,12.757c-0.009,0.788-0.627,1.443-1.408,1.493C31.799,34.063,30.131,34.104,28.458,34.104z M14.654,19.42c-0.249,0-0.461,0.187-0.491,0.436c-0.221,1.783-0.427,3.685-0.299,5.604l0.084,1.194 c0.14,1.934,0.26,3.604,0.107,5.554c-0.01,0.133,0.034,0.261,0.126,0.36c0.094,0.104,0.223,0.161,0.363,0.163 c2.027,0.028,4.108,0.105,6.121,0.181c4.197,0.154,8.541,0.317,12.715,0.049c0.262-0.017,0.469-0.239,0.472-0.507 c0.046-4.231,0.021-8.512-0.071-12.724c-0.003-0.135-0.058-0.26-0.154-0.352c-0.096-0.094-0.246-0.131-0.356-0.138 C27.897,19.373,21.997,19.43,14.654,19.42L14.654,19.42L14.654,19.42z"></path><path d="M22.299,25.095c-0.004,0-0.009,0-0.013,0l-5.16-0.133c-0.239-0.006-0.44-0.181-0.48-0.416 c-0.106-0.629-0.129-1.391-0.068-2.33c0.018-0.27,0.208-0.484,0.519-0.468c0.862,0.033,1.737,0.044,2.612,0.056 c0.874,0.011,1.748,0.021,2.609,0.054c0.269,0.011,0.481,0.231,0.481,0.5v2.237c0,0.135-0.055,0.264-0.151,0.358 C22.555,25.044,22.429,25.095,22.299,25.095z M17.579,23.973l4.22,0.109v-1.241c-0.696-0.021-1.4-0.029-2.104-0.037 c-0.717-0.01-1.434-0.019-2.145-0.039C17.536,23.22,17.545,23.62,17.579,23.973z"></path><path d="M30.505,25.286c-0.011,0-0.021,0-0.029,0l-4.611-0.008c-0.141,0-0.274-0.06-0.369-0.163 c-0.095-0.104-0.141-0.243-0.128-0.383c0.081-0.871,0.029-1.607-0.164-2.316c-0.053-0.192,0.015-0.398,0.172-0.522 c0.464-0.368,1.063-0.368,1.541-0.357l4.069,0.014c0.136,0,0.271,0.057,0.366,0.155c0.094,0.099,0.149,0.231,0.143,0.367 l-0.109,2.375c-0.005,0.116-0.015,0.362-0.192,0.568C30.97,25.271,30.653,25.286,30.505,25.286z M26.398,24.279l3.992,0.007 l0.081-1.738l-3.565-0.012c-0.217-0.005-0.45-0.005-0.64,0.032C26.378,23.11,26.421,23.669,26.398,24.279z"></path><path d="M25.738,31.013c-0.131,0-0.257-0.052-0.351-0.144c-0.095-0.093-0.148-0.221-0.149-0.354l-0.01-2.854 c-0.001-0.275,0.221-0.5,0.496-0.502c1.588-0.014,3.404,0.097,4.871,0.185c0.134,0.008,0.488,0.028,0.698,0.33 c0.138,0.196,0.135,0.413,0.134,0.506c-0.003,0.558-0.042,1.597-0.119,2.15c-0.009,0.071-0.035,0.275-0.206,0.438 c-0.197,0.176-0.414,0.179-0.507,0.181l-4.851,0.063C25.743,31.013,25.74,31.013,25.738,31.013z M26.23,28.16l0.006,1.846 l4.109-0.054c0.045-0.472,0.072-1.147,0.08-1.613C29.105,28.258,27.592,28.171,26.23,28.16z"></path><path d="M16.762,30.889c-0.134,0-0.262-0.054-0.355-0.148c-0.097-0.098-0.149-0.231-0.145-0.369 c0.01-0.276,0.004-0.583-0.002-0.889c-0.013-0.691-0.025-1.344,0.115-1.81c0.054-0.181,0.205-0.315,0.391-0.349 c1.806-0.324,3.709-0.11,5.502,0.139c0.247,0.034,0.432,0.245,0.432,0.494l0.002,2.299c0,0.272-0.216,0.494-0.488,0.501 l-5.439,0.132C16.77,30.889,16.766,30.889,16.762,30.889z M17.279,28.255c-0.036,0.337-0.027,0.793-0.02,1.209 c0.003,0.139,0.005,0.276,0.007,0.412l4.435-0.107l-0.002-1.374C20.233,28.201,18.711,28.061,17.279,28.255z"></path>
                                            </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Orders') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="{{ route('orders.index') }}">
                                                {{ __('All') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('orders.complete') }}">
                                                {{ __('Completed') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('orders.pending') }}">
                                                {{ __('Pending') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('due.index') }}">
                                                {{ __('Due') }}
                                            </a>
                                            {{-- <a class="dropdown-item" href="{{ route('dispatch.print') }}">
                                                {{ __('Print orders') }}
                                            </a> --}}
                                            <a class="dropdown-item" href="{{ route('fetchingsheets') }}">
                                                {{ __('Google Sheets') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>


                            {{-- <li class="nav-item dropdown {{ request()->is('purchases*') ? 'active' : null }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-package-import" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 21l-8 -4.5v-9l8 -4.5l8 4.5v4.5" />
                                            <path d="M12 12l8 -4.5" />
                                            <path d="M12 12v9" />
                                            <path d="M12 12l-8 -4.5" />
                                            <path d="M22 18h-7" />
                                            <path d="M18 15l-3 3l3 3" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Purchases') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="{{ route('purchases.index') }}">
                                                {{ __('All') }}
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('purchases.approvedPurchases') }}">
                                                {{ __('Approval') }}
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('purchases.purchaseReport') }}">
                                                {{ __('Daily Purchase Report') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li> --}}



                           



                            <li
                                class="nav-item dropdown {{ request()->is('suppliers*', 'customers*') ? 'active' : null }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-layers-subtract" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M8 4m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                            <path d="M16 16v2a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-8a2 2 0 0 1 2 -2h2" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Pages') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="{{ route('suppliers.index') }}">
                                                {{ __('Suppliers') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('distributors.index') }}">
                                                {{ __('Distributors') }}
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </li>


                            <li
                                class="nav-item dropdown {{ request()->is('users*', 'categories*', 'units*') ? 'active' : null }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-settings" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Settings') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            {{-- <a class="dropdown-item" href="{{ route('users.index') }}">
                                                    {{ __('Users') }}
                                                </a> --}}
                                            <a class="dropdown-item" href="{{ route('categories.index') }}">
                                                {{ __('Categories') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('units.index') }}">
                                                {{ __('Merchants') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                            <form action="./" method="get" autocomplete="off" novalidate>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                            <path d="M21 21l-6 -6" />
                                        </svg>
                                    </span>
                                    <input type="text" name="search" id="search" value=""
                                        class="form-control" placeholder="Search…" aria-label="Search in website">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="page-wrapper">
            <div>
                @yield('content')
            </div>

            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; {{ now()->year }}
                                    <a href="." class="link-secondary">Maikol</a>.
                                    All rights reserved.
                                </li>
                                <li class="list-inline-item">
                                    <a href="./changelog.html" class="link-secondary" rel="noopener">
                                    version 1.0
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Libs JS -->
    @stack('page-libraries')
    <!-- Tabler Core -->
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('dist/js/demo.min.js') }}" defer></script>
    {{-- - Page Scripts - --}}
    @stack('page-scripts')

    @livewireScripts
</body>

</html>
