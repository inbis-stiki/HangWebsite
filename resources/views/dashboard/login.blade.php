<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css" media="all">
</head>

<body class="">
    <div class="grid grid-cols-5 h-screen">
        <div class="col-span-3 h-screen bg-finna">
            <!-- <img src="img/bglogin.png" class="h-screen w-full" alt=""> -->
        </div>
        <div class="col-span-2 h-screen">
            <div class="mx-auto px-20 mt-20">
                <img src="{{ asset('img/finna-smol.png') }}" class="mx-auto" alt="">
                <span class="text-4xl font-bold inline-block mt-16 warna-login">Masuk</span>
                <form action="{{ url('auth') }}" method="post">
                    @csrf
                    <div class="mt-3 space-y-4">
                        <div class="flex flex-col">
                            <span class="font-normal text-xs">Username</span>
                            <input type="text" name="username" id="input_username" class="box-border h-12 mt-2 border-solid border border-gray-200 rounded py-4 px-6 focus:outline-orange-500 focus:outline-2 focus:ring-0 focus:border-gray-200" placeholder="Username" required>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-normal text-xs">Password</span>
                            <input type="password" name="password" id="input_password" class="box-border h-12 mt-2 border-solid border border-gray-200 rounded py-4 px-6 focus:outline-orange-500 focus:outline-2 focus:ring-0 focus:border-gray-200" placeholder="Password" required>
                        </div>
                    </div>
                    @if (session('err_msg'))
                    <br>
                    <div class="alert alert-danger">{!! session('err_msg') !!}</div>
                    @endif
                    <button type="submit" class="w-1/2 bg-orange-500 mt-8 text-white py-3 rounded-md">Masuk</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>