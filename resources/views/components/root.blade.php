@props(['title'])

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $title }}</title>
        <meta charset="UTF-8">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta property="og:title" content="{{ $title }}" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ request()->url() }}" />
        <meta property="og:description" content="Test a vending machine service concept." />
        <meta property="og:image" content="{{ asset('img/image.png') }}" />

        <meta name="description" content="Test a vending machine service concept.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="Vending, Machine, Service, Concept, Test">
        <meta name="author" content="{{ env("AUTHOR", "Unknown") }}">
        <meta name="theme-color" content="#282828">

        <meta name="robots" content="noindex">
        <meta name="googlebot" content="noindex">

        <link rel="icon" href="{{ asset('favicon.png') }}">
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
        <script type="text/javascript" src="{{ asset('/js/app.js') }}"></script>
    </head>

    <body>
        <div id="body">
            {{ $slot }}
        </div>
    </body>
</html>
