<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css" media="all">
</head>

<body class="bg-gray-100">
    <div class="grid grid-cols-9">
        @include('dashboard.layouts.sidebar')
        <main class="md:col-span-7 col-span-9">
            @include('dashboard.layouts.navbar')
            <div class="bg-gray-100 min-h-screen py-8">
                <div class="px-12 mx-auto">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    @yield('footer')
</body>
<script src="{{asset('js/app.js')}}"></script>


</html>