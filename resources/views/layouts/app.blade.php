<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <style>
            .form-input-custom {
                @apply mt-2 block w-full px-4 py-3.5 border border-gray-300 rounded-lg shadow-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none
                       text-gray-900 bg-white transition-all duration-200 ease-in-out
                       hover:border-gray-400 placeholder-gray-400;
            }
            .form-input-custom:focus {
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
            }
            .form-select-custom {
                @apply mt-2 block w-full pl-4 pr-10 py-3.5 text-base border border-gray-300 rounded-lg shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                       bg-white transition-all duration-200 ease-in-out hover:border-gray-400;
            }
            .form-select-custom:focus {
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
            }
            .form-button-custom {
                @apply inline-flex items-center justify-center py-3.5 px-8 border border-transparent shadow-sm text-base font-medium
                       rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2
                       focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out;
            }
            .label-custom {
                @apply flex text-sm font-semibold text-gray-700 mb-2 items-center;
            }
            .field-section {
                @apply p-6 bg-white rounded-lg border border-gray-200 shadow-sm mb-6;
            }
            .field-section-title {
                @apply text-lg font-bold text-gray-800 mb-4 flex items-center;
            }
            .notification-custom {
                @apply px-6 py-4 rounded-lg shadow-sm border-l-4 mb-6;
            }
        </style>

        <!-- Scripts -->
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex h-screen">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 ml-64 overflow-y-auto">
                @yield('content')
            </div>
        </div>
    </body>
</html>
