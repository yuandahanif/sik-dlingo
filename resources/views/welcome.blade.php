<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dlingo SIK</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="bg-gray-50 text-black/50 flex h-screen w-screen justify-center items-center flex-col">
        <img id="background" class="w-[400px] h-auto" src="{{ asset('images/logo-bantul-sid.png') }}" />
        <h1 class="text-2xl text-bold ">Kalurahan Dlingo Kapanewon Dlingo Kabupaten Bantul D.I.Yogyakarta Koripan 1, Dlingo,
            Dlingo, Bantul.</h1>
    </div>
</body>

</html>
