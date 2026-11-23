<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#183d35">
    <meta name="description" content="@yield('description', 'Profil profesional Kalfin Alloto’dang. Teknologi, pendidikan, dan kepemimpinan dari Makassar, Indonesia.')">
    <title>@yield('title', 'Kalfin Alloto’dang — Personal Portfolio')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body-class')">
    <a class="skip-link" href="#main">Lewati ke konten</a>
    @yield('content')
</body>
</html>
