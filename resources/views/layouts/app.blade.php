<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rathdrum Community First Responders</title>
    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen">
@include('partials.header')

<main class="max-w-7xl min-w-3/4 mx-auto sm:px-6 lg:px-8 flex-grow">
    {{ $slot }}
</main>

@include('partials.footer')
</body>
</html>
