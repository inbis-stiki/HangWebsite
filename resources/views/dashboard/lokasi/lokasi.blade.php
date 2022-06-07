<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css" media="all">
</head>

<body class="grid grid-cols-9">
    <aside class="self-start sticky top-0 col-span-2 px-5 py-9 border-r border-gray-200 hidden md:block">
        <img src="../img/finna.png" class="w-32 mx-auto" alt="">
        <div class="mt-6 space-y-4">
            <div>
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22" class="w-6 h-6 fill-gray-300">
                        <path d="m18.667 6.667-6.5-5.699a3.25 3.25 0 0 0-4.334 0l-6.5 5.699A3.25 3.25 0 0 0 .25 9.115v9.468a3.25 3.25 0 0 0 3.25 3.25h13a3.25 3.25 0 0 0 3.25-3.25V9.104a3.25 3.25 0 0 0-1.083-2.437Zm-6.5 13H7.833V14.25a1.083 1.083 0 0 1 1.084-1.083h2.166a1.083 1.083 0 0 1 1.084 1.083v5.417Zm5.416-1.084a1.083 1.083 0 0 1-1.083 1.084h-2.167V14.25a3.25 3.25 0 0 0-3.25-3.25H8.917a3.25 3.25 0 0 0-3.25 3.25v5.417H3.5a1.084 1.084 0 0 1-1.083-1.084V9.104a1.083 1.083 0 0 1 .368-.812l6.5-5.688a1.083 1.083 0 0 1 1.43 0l6.5 5.688a1.083 1.083 0 0 1 .368.812v9.48Z" />
                    </svg>
                    <span class="font-medium text-gray-300">Dashboard</span>
                </a>
            </div>
            <div class="space-y-4 overflow-y">
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7 bg-orange-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26" class="w-6 h-6 fill-orange-500">
                        <path d="M17.333 21.667H8.667a3.25 3.25 0 0 1-3.25-3.25V7.583a1.083 1.083 0 0 0-2.167 0v10.834a5.417 5.417 0 0 0 5.417 5.416h8.666a1.083 1.083 0 1 0 0-2.166Zm-6.5-7.584a1.083 1.083 0 0 0 1.084 1.084h5.416a1.083 1.083 0 1 0 0-2.167h-5.416a1.083 1.083 0 0 0-1.084 1.083ZM22.75 9.685a1.422 1.422 0 0 0-.065-.292v-.098a1.16 1.16 0 0 0-.206-.303l-6.5-6.5a1.16 1.16 0 0 0-.303-.206.347.347 0 0 0-.098 0 .953.953 0 0 0-.357-.12h-4.388a3.25 3.25 0 0 0-3.25 3.25V16.25a3.25 3.25 0 0 0 3.25 3.25H19.5a3.25 3.25 0 0 0 3.25-3.25V9.685Zm-6.5-3.824 2.806 2.806h-1.723a1.083 1.083 0 0 1-1.083-1.084V5.861Zm4.333 10.39a1.083 1.083 0 0 1-1.083 1.082h-8.667A1.083 1.083 0 0 1 9.75 16.25V5.417a1.083 1.083 0 0 1 1.083-1.084h3.25v3.25c.003.37.07.736.195 1.084h-2.361a1.083 1.083 0 1 0 0 2.166h8.666v5.417Z" />
                    </svg>
                    <span class="font-semibold text-orange-500">Master</span>
                </a>
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7 ">
                    <div class="w-6 h-6">
                    </div>
                    <span class="text-gray-300">Kategori Produk</span>
                </a>
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7 ">
                    <div class="w-6 h-6">
                    </div>
                    <span class="text-gray-300">Produk</span>
                </a>
                <div class=" px-4 py-3 space-y-5">

                    <a href="#" class="flex flex-row rounded-lg space-x-7 ">
                        <div class="w-6 h-6 flex items-stretch">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 6 15" class="w-1.5 h-3.5 self-center mx-auto fill-orange-500">
                                <path d="M.003 11.27V3.73a.586.586 0 0 1 1-.414l3.77 3.77c.228.229.228.6 0 .828l-3.77 3.77a.586.586 0 0 1-1-.415Z" />
                            </svg>
                        </div>
                        <span class="text-orange-500">Lokasi</span>
                    </a>
                    <div class="flex flex-row space-x-7">
                        <div class="w-6 h-6"></div>
                        <div class="px-4">
                            <span class="text-gray-300">Region</span>
                        </div>
                    </div>
                    <div class="flex flex-row space-x-7">
                        <div class="w-6 h-6"></div>
                        <div class="px-4">
                            <span class="text-orange-500">Lokasi</span>
                        </div>
                    </div>
                    <div class="flex flex-row space-x-7">
                        <div class="w-6 h-6"></div>
                        <div class="px-4">
                            <span class="text-gray-300">Area</span>
                        </div>
                    </div>
                    <div class="flex flex-row space-x-7">
                        <div class="w-6 h-6"></div>
                        <div class="px-4">
                            <span class="text-gray-300">Kecamatan</span>
                        </div>
                    </div>
                    <div class="flex flex-row space-x-7">
                        <div class="w-6 h-6"></div>
                        <div class="px-4">
                            <span class="text-gray-300">Pasar</span>
                        </div>
                    </div>
                </div>
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7 ">
                    <div class="w-6 h-6">
                    </div>
                    <span class="text-gray-300">Kategori Produk</span>
                </a>
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7 ">
                    <div class="w-6 h-6">
                    </div>
                    <span class="text-gray-300">Kategori Produk</span>
                </a>
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7 ">
                    <div class="w-6 h-6">
                    </div>
                    <span class="text-gray-300">Kategori Produk</span>
                </a>
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7 ">
                    <div class="w-6 h-6">
                    </div>
                    <span class="text-gray-300">Kategori Produk</span>
                </a>
            </div>
        </div>
    </aside>

    <main class="md:col-span-7 col-span-9">
        <nav class="border-b border-gray-200 border sticky top-0 bg-white">
            <div class="flex flex-row px-12 mx-auto py-3 justify-between items-center">
                <div class="bg-gray-200 px-4 py-3.5 rounded-xl flex flex-row w-3/5">
                    <img src="../img/search-dashboard.svg" alt="">
                    <input type="text" class="w-full bg-gray-200 px-6 focus:outline-none" placeholder="Pencarian">
                </div>
                <div class="flex flex-row space-x-8">
                    <img src="../img/bell-dashboard.svg" alt="">
                    <div class="flex flex-row space-x-4 items-center">
                        <img src="../img/wong.png" class="rounded-full" alt="">
                        <span class="font-medium">Jenenge Wong</span>
                        <img src="../img/dropdown-dashboard.svg" alt="">
                    </div>
                </div>
            </div>
        </nav>
        <div class="bg-gray-100 min-h-screen py-8">
            <div class="px-12 mx-auto">
                <div class="flex flex-row justify-between items-center">
                    <span class="font-bold text-lg">Lokasi</span>
                    <button class="p-3 bg-orange-400 text-white flex flex-row items-center space-x-3 rounded-lg ">
                        <img src="../img/plus-dashboard.svg" alt="">
                        <span class="text-sm">Tambah Lokasi</span>
                    </button>
                </div>
                <table class="w-full mt-7">
                    <thead>
                        <tr class="bg-white mx-9 rounded-lg  border-b">
                            <th class="text-left text-gray-400 text-sm py-3 pl-9  rounded-tl-lg">
                                Lokasi</th>
                            <th class="text-left text-gray-400 text-sm py-3">
                                Region</th>
                            <th class="text-left text-gray-400 text-sm py-3">Status</th>
                            <th class="text-left text-gray-400 text-sm py-3 pr-9  rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="mx-9 bg-white odd:bg-gray-50 even:bg-white">
                            <td class="pl-9 py-5">
                                Sumatera
                            </td>
                            <td class="">
                                Sumatera
                            </td>
                            <td class="">
                                <div class="flex flex-row space-x-3 items-center">
                                    <div class="rounded-full bg-green-500 h-3 w-3">

                                    </div>
                                    <span>Enable</span>
                                </div>
                            </td>
                            <td class="text-gray-400">
                                <div class="flex flex-row items-center space-x-3">
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" class="w-4 h-4 fill-white">
                                            <path d="m14.587 4.163-1.35 1.35a.352.352 0 0 1-.499 0L9.486 2.261a.352.352 0 0 1 0-.498l1.35-1.35a1.41 1.41 0 0 1 1.99 0l1.76 1.76c.552.548.552 1.44 0 1.99Zm-6.26-1.24L.632 10.618l-.621 3.56a.704.704 0 0 0 .814.814l3.56-.624 7.693-7.693a.352.352 0 0 0 0-.498L8.827 2.924a.355.355 0 0 0-.5 0ZM3.635 9.959a.408.408 0 0 1 0-.58l4.511-4.512a.408.408 0 0 1 .58 0 .408.408 0 0 1 0 .58L4.217 9.958a.408.408 0 0 1-.58 0Zm-1.058 2.464h1.406v1.063l-1.89.331-.91-.911.33-1.89h1.064v1.407Z" />
                                        </svg>
                                    </a>
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 15" class="w-4 h-4 fill-white">
                                            <path d="M.94 13.594A1.406 1.406 0 0 0 2.346 15h8.438a1.406 1.406 0 0 0 1.406-1.406V3.75H.94v9.844Zm7.969-7.5a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094Zm-2.813 0a.469.469 0 0 1 .938 0v6.562a.469.469 0 0 1-.938 0V6.094Zm-2.812 0a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094ZM12.659.938H9.143L8.868.39a.703.703 0 0 0-.63-.39H4.889a.695.695 0 0 0-.627.39l-.275.548H.47a.469.469 0 0 0-.468.468v.938a.469.469 0 0 0 .468.469h12.19a.469.469 0 0 0 .468-.47v-.937a.469.469 0 0 0-.468-.468Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="mx-9 bg-white odd:bg-gray-50 even:bg-white">
                            <td class="pl-9 py-5">
                                Central
                            </td>
                            <td>Central</td>
                            <td class="">
                                <div class="flex flex-row space-x-3 items-center">
                                    <div class="rounded-full bg-green-500 h-3 w-3">

                                    </div>
                                    <span>Enable</span>
                                </div>
                            </td>
                            <td class="text-gray-400">
                                <div class="flex flex-row items-center space-x-3">
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" class="w-4 h-4 fill-white">
                                            <path d="m14.587 4.163-1.35 1.35a.352.352 0 0 1-.499 0L9.486 2.261a.352.352 0 0 1 0-.498l1.35-1.35a1.41 1.41 0 0 1 1.99 0l1.76 1.76c.552.548.552 1.44 0 1.99Zm-6.26-1.24L.632 10.618l-.621 3.56a.704.704 0 0 0 .814.814l3.56-.624 7.693-7.693a.352.352 0 0 0 0-.498L8.827 2.924a.355.355 0 0 0-.5 0ZM3.635 9.959a.408.408 0 0 1 0-.58l4.511-4.512a.408.408 0 0 1 .58 0 .408.408 0 0 1 0 .58L4.217 9.958a.408.408 0 0 1-.58 0Zm-1.058 2.464h1.406v1.063l-1.89.331-.91-.911.33-1.89h1.064v1.407Z" />
                                        </svg>
                                    </a>
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 15" class="w-4 h-4 fill-white">
                                            <path d="M.94 13.594A1.406 1.406 0 0 0 2.346 15h8.438a1.406 1.406 0 0 0 1.406-1.406V3.75H.94v9.844Zm7.969-7.5a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094Zm-2.813 0a.469.469 0 0 1 .938 0v6.562a.469.469 0 0 1-.938 0V6.094Zm-2.812 0a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094ZM12.659.938H9.143L8.868.39a.703.703 0 0 0-.63-.39H4.889a.695.695 0 0 0-.627.39l-.275.548H.47a.469.469 0 0 0-.468.468v.938a.469.469 0 0 0 .468.469h12.19a.469.469 0 0 0 .468-.47v-.937a.469.469 0 0 0-.468-.468Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="mx-9 bg-white odd:bg-gray-50 even:bg-white">
                            <td class="pl-9 py-5">
                                East Dapul
                            </td>
                            <td>East Dapul</td>
                            <td class="">
                                <div class="flex flex-row space-x-3 items-center">
                                    <div class="rounded-full bg-red-500 h-3 w-3">

                                    </div>
                                    <span>Enable</span>
                                </div>
                            </td>
                            <td class="text-gray-400">
                                <div class="flex flex-row items-center space-x-3">
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" class="w-4 h-4 fill-white">
                                            <path d="m14.587 4.163-1.35 1.35a.352.352 0 0 1-.499 0L9.486 2.261a.352.352 0 0 1 0-.498l1.35-1.35a1.41 1.41 0 0 1 1.99 0l1.76 1.76c.552.548.552 1.44 0 1.99Zm-6.26-1.24L.632 10.618l-.621 3.56a.704.704 0 0 0 .814.814l3.56-.624 7.693-7.693a.352.352 0 0 0 0-.498L8.827 2.924a.355.355 0 0 0-.5 0ZM3.635 9.959a.408.408 0 0 1 0-.58l4.511-4.512a.408.408 0 0 1 .58 0 .408.408 0 0 1 0 .58L4.217 9.958a.408.408 0 0 1-.58 0Zm-1.058 2.464h1.406v1.063l-1.89.331-.91-.911.33-1.89h1.064v1.407Z" />
                                        </svg>
                                    </a>
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 15" class="w-4 h-4 fill-white">
                                            <path d="M.94 13.594A1.406 1.406 0 0 0 2.346 15h8.438a1.406 1.406 0 0 0 1.406-1.406V3.75H.94v9.844Zm7.969-7.5a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094Zm-2.813 0a.469.469 0 0 1 .938 0v6.562a.469.469 0 0 1-.938 0V6.094Zm-2.812 0a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094ZM12.659.938H9.143L8.868.39a.703.703 0 0 0-.63-.39H4.889a.695.695 0 0 0-.627.39l-.275.548H.47a.469.469 0 0 0-.468.468v.938a.469.469 0 0 0 .468.469h12.19a.469.469 0 0 0 .468-.47v-.937a.469.469 0 0 0-.468-.468Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="mx-9 bg-white odd:bg-gray-50 even:bg-white">
                            <td class="pl-9 py-5">
                                East Lapul
                            </td>
                            <td>East Lapul</td>
                            <td class="">
                                <div class="flex flex-row space-x-3 items-center">
                                    <div class="rounded-full bg-green-500 h-3 w-3">

                                    </div>
                                    <span>Enable</span>
                                </div>
                            </td>
                            <td class="text-gray-400">
                                <div class="flex flex-row items-center space-x-3">
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" class="w-4 h-4 fill-white">
                                            <path d="m14.587 4.163-1.35 1.35a.352.352 0 0 1-.499 0L9.486 2.261a.352.352 0 0 1 0-.498l1.35-1.35a1.41 1.41 0 0 1 1.99 0l1.76 1.76c.552.548.552 1.44 0 1.99Zm-6.26-1.24L.632 10.618l-.621 3.56a.704.704 0 0 0 .814.814l3.56-.624 7.693-7.693a.352.352 0 0 0 0-.498L8.827 2.924a.355.355 0 0 0-.5 0ZM3.635 9.959a.408.408 0 0 1 0-.58l4.511-4.512a.408.408 0 0 1 .58 0 .408.408 0 0 1 0 .58L4.217 9.958a.408.408 0 0 1-.58 0Zm-1.058 2.464h1.406v1.063l-1.89.331-.91-.911.33-1.89h1.064v1.407Z" />
                                        </svg>
                                    </a>
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 15" class="w-4 h-4 fill-white">
                                            <path d="M.94 13.594A1.406 1.406 0 0 0 2.346 15h8.438a1.406 1.406 0 0 0 1.406-1.406V3.75H.94v9.844Zm7.969-7.5a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094Zm-2.813 0a.469.469 0 0 1 .938 0v6.562a.469.469 0 0 1-.938 0V6.094Zm-2.812 0a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094ZM12.659.938H9.143L8.868.39a.703.703 0 0 0-.63-.39H4.889a.695.695 0 0 0-.627.39l-.275.548H.47a.469.469 0 0 0-.468.468v.938a.469.469 0 0 0 .468.469h12.19a.469.469 0 0 0 .468-.47v-.937a.469.469 0 0 0-.468-.468Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="mx-9 bg-white odd:bg-gray-50 even:bg-white">
                            <td class="pl-9 py-5">
                                West
                            </td>
                            <td>
                                West
                            </td>
                            <td class="">
                                <div class="flex flex-row space-x-3 items-center">
                                    <div class="rounded-full bg-green-500 h-3 w-3">

                                    </div>
                                    <span>Enable</span>
                                </div>
                            </td>
                            <td class="text-gray-400">
                                <div class="flex flex-row items-center space-x-3">
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" class="w-4 h-4 fill-white">
                                            <path d="m14.587 4.163-1.35 1.35a.352.352 0 0 1-.499 0L9.486 2.261a.352.352 0 0 1 0-.498l1.35-1.35a1.41 1.41 0 0 1 1.99 0l1.76 1.76c.552.548.552 1.44 0 1.99Zm-6.26-1.24L.632 10.618l-.621 3.56a.704.704 0 0 0 .814.814l3.56-.624 7.693-7.693a.352.352 0 0 0 0-.498L8.827 2.924a.355.355 0 0 0-.5 0ZM3.635 9.959a.408.408 0 0 1 0-.58l4.511-4.512a.408.408 0 0 1 .58 0 .408.408 0 0 1 0 .58L4.217 9.958a.408.408 0 0 1-.58 0Zm-1.058 2.464h1.406v1.063l-1.89.331-.91-.911.33-1.89h1.064v1.407Z" />
                                        </svg>
                                    </a>
                                    <a href="#" class="bg-orange-500 py-1 px-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 15" class="w-4 h-4 fill-white">
                                            <path d="M.94 13.594A1.406 1.406 0 0 0 2.346 15h8.438a1.406 1.406 0 0 0 1.406-1.406V3.75H.94v9.844Zm7.969-7.5a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094Zm-2.813 0a.469.469 0 0 1 .938 0v6.562a.469.469 0 0 1-.938 0V6.094Zm-2.812 0a.469.469 0 0 1 .937 0v6.562a.469.469 0 0 1-.937 0V6.094ZM12.659.938H9.143L8.868.39a.703.703 0 0 0-.63-.39H4.889a.695.695 0 0 0-.627.39l-.275.548H.47a.469.469 0 0 0-.468.468v.938a.469.469 0 0 0 .468.469h12.19a.469.469 0 0 0 .468-.47v-.937a.469.469 0 0 0-.468-.468Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>

</html>