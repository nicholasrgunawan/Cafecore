<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Café Core')</title>
    @vite('resources/css/app.css')
</head>
<body>
    @yield('content')
  </body>
</html>