<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('assets/css/kasir.css') }}" rel="stylesheet" />
    <title>@yield('tittle')</title>
</head>

<body>
    <main>
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    @include('sweetalert::alert')

</body>

</html>
