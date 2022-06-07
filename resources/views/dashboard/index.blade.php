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
        <img src="{{asset('img/finna.png')}}" class="w-32 mx-auto" alt="">
        <div class="mt-6 space-y-4">
            <div>
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 bg-orange-100 space-x-7">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22" class="w-6 h-6 fill-orange-500">
                        <path d="m18.667 6.667-6.5-5.699a3.25 3.25 0 0 0-4.334 0l-6.5 5.699A3.25 3.25 0 0 0 .25 9.115v9.468a3.25 3.25 0 0 0 3.25 3.25h13a3.25 3.25 0 0 0 3.25-3.25V9.104a3.25 3.25 0 0 0-1.083-2.437Zm-6.5 13H7.833V14.25a1.083 1.083 0 0 1 1.084-1.083h2.166a1.083 1.083 0 0 1 1.084 1.083v5.417Zm5.416-1.084a1.083 1.083 0 0 1-1.083 1.084h-2.167V14.25a3.25 3.25 0 0 0-3.25-3.25H8.917a3.25 3.25 0 0 0-3.25 3.25v5.417H3.5a1.084 1.084 0 0 1-1.083-1.084V9.104a1.083 1.083 0 0 1 .368-.812l6.5-5.688a1.083 1.083 0 0 1 1.43 0l6.5 5.688a1.083 1.083 0 0 1 .368.812v9.48Z" />
                    </svg>
                    <span class="font-semibold primary-orange">Dashboard</span>
                </a>
            </div>
            <div class="space-y-4 overflow-y">
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26" class="w-6 h-6 fill-gray-300">
                        <path d="M17.333 21.667H8.667a3.25 3.25 0 0 1-3.25-3.25V7.583a1.083 1.083 0 0 0-2.167 0v10.834a5.417 5.417 0 0 0 5.417 5.416h8.666a1.083 1.083 0 1 0 0-2.166Zm-6.5-7.584a1.083 1.083 0 0 0 1.084 1.084h5.416a1.083 1.083 0 1 0 0-2.167h-5.416a1.083 1.083 0 0 0-1.084 1.083ZM22.75 9.685a1.422 1.422 0 0 0-.065-.292v-.098a1.16 1.16 0 0 0-.206-.303l-6.5-6.5a1.16 1.16 0 0 0-.303-.206.347.347 0 0 0-.098 0 .953.953 0 0 0-.357-.12h-4.388a3.25 3.25 0 0 0-3.25 3.25V16.25a3.25 3.25 0 0 0 3.25 3.25H19.5a3.25 3.25 0 0 0 3.25-3.25V9.685Zm-6.5-3.824 2.806 2.806h-1.723a1.083 1.083 0 0 1-1.083-1.084V5.861Zm4.333 10.39a1.083 1.083 0 0 1-1.083 1.082h-8.667A1.083 1.083 0 0 1 9.75 16.25V5.417a1.083 1.083 0 0 1 1.083-1.084h3.25v3.25c.003.37.07.736.195 1.084h-2.361a1.083 1.083 0 1 0 0 2.166h8.666v5.417Z" />
                    </svg>
                    <span class="font-medium text-gray-300">Master</span>
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
                <a href="#" class="flex flex-row rounded-lg px-4 py-3 space-x-7 ">
                    <div class="w-6 h-6 flex items-stretch">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 6 15" class="w-1.5 h-3.5 self-center mx-auto fill-gray-300">
                            <path d="M.003 11.27V3.73a.586.586 0 0 1 1-.414l3.77 3.77c.228.229.228.6 0 .828l-3.77 3.77a.586.586 0 0 1-1-.415Z" />
                        </svg>
                    </div>
                    <span class="text-gray-300">Lokasi</span>
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
                    <img src="{{asset('img/search-dashboard.svg')}}" alt="">
                    <input type="text" class="w-full bg-gray-200 px-6 focus:outline-none" placeholder="Pencarian">
                </div>
                <div class="flex flex-row space-x-8">
                    <img src="{{asset('img/bell-dashboard.svg')}}" alt="">
                    <div class="flex flex-row space-x-4 items-center">
                        <img src="{{asset('img/wong.png')}}" class="rounded-full" alt="">
                        <span class="font-medium">Jenenge Wong</span>
                        <img src="{{asset('img/dropdown-dashboard.svg')}}" alt="">
                    </div>
                </div>
            </div>
        </nav>
        <div class="bg-gray-100 min-h-screen py-8">
            <div class="px-12 mx-auto">
                <span class="text-base font-bold">Analitik</span>
                <div class="mt-4 grid grid-cols-4 gap-2">
                    <div class="bg-white py-8 px-4 rounded-lg flex flex-row space-x-2 w-full">
                        <img src="{{asset('img/total-pendapatan.png')}}" alt="">
                        <div class="flex flex-col justify-between w-full">
                            <span class="text-sm text-gray-400">Total Pendapatan</span>
                            <div class="flex flex-row justify-between">
                                <span class="spartan font-extrabold">Rp 5JT</span>
                                <div class="bg-green-200 text-green-700 text-xs font-bold rounded-lg py-1 px-2">+3,4%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white py-8 px-4 rounded-lg flex flex-row space-x-2 w-full">
                        <img src="{{asset('img/today-revenue.png')}}" alt="">
                        <div class="flex flex-col justify-between w-full">
                            <span class="text-sm text-gray-400">Pendapatan Hari Ini</span>
                            <div class="flex flex-row justify-between">
                                <span class="spartan font-extrabold">Rp 5JT</span>
                                <div class="bg-red-200 text-red-700 text-xs font-bold rounded-lg py-1 px-2">+3,4%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white py-8 px-4 rounded-lg flex flex-row space-x-2 w-full">
                        <img src="{{asset('img/barang-terjual.png')}}" alt="">
                        <div class="flex flex-col justify-between w-full">
                            <span class="text-sm text-gray-400">Barang Terjual</span>
                            <div class="flex flex-row justify-between">
                                <span class="spartan font-extrabold">22</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white py-8 px-4 rounded-lg flex flex-row space-x-2 w-full">
                        <img src="{{asset('img/total-sales.png')}}" alt="">
                        <div class="flex flex-col justify-between w-full">
                            <span class="text-sm text-gray-400">Total Sales</span>
                            <div class="flex flex-row justify-between">
                                <span class="spartan font-extrabold">Rp 5JT</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3 bg-white py-8 px-4 rounded-lg space-y-3">
                        <div class=" flex flex-row justify-between items-center">
                            <div class="flex flex-row space-x-8 items-center">
                                <span class="font-bold">Rapor</span>
                                <div>
                                    <select name="penjualan" id="" class="py-1.5 px-3 border-gray-200 bg-white border rounded-lg text-xs text-gray-400">
                                        <option selected>Pilih salah satu</option>
                                        <option>Penjualan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-row justify-between space-x-4">
                                <span class="text-xs text-gray-300 font-bold">Hari</span>
                                <span class="text-xs text-orange-400 font-bold">Minggu</span>
                                <span class="text-xs text-gray-300 font-bold">Bulan</span>
                                <span class="text-xs text-gray-300 font-bold">Tahun</span>
                            </div>
                        </div>
                        <canvas id="chartLine" class="w-full h-25"></canvas>
                    </div>
                    <div class="col-span-1 bg-white py-8 px-4 rounded-lg">
                        <div class="flex flex-row justify-between items-center">
                            <span class="font-bold">Faktur</span>
                            <div>
                                <div class="flex flex-row justify-between space-x-4">
                                    <span class="text-xs text-gray-300 font-bold">H</span>
                                    <span class="text-xs text-orange-400 font-bold">M</span>
                                    <span class="text-xs text-gray-300 font-bold">B</span>
                                    <span class="text-xs text-gray-300 font-bold">T</span>
                                </div>

                            </div>
                        </div>
                        <div class="w-full h-96">
                            <canvas id="chartFaktur" style="width: 100% !important;height: 100% !important;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center mt-4">
                    <span class="text-base font-bold inline-block">Penjualan</span>
                    <a href="#" class="flex flex-row items-center space-x-2">
                        <span class="text-gray-400 text-sm font-medium">Tampilkan Semua</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-4 gap-2 mt-4">
                    <div class="col-span-4 bg-white py-8 px-4 rounded-lg">
                        <div class="flex flex-row space-x-2">
                            <div class="py-1.5 px-3 border-gray-200 bg-white border rounded-lg text-xs text-gray-400 flex flex-row items-center ">
                                <img src="{{asset('img/locate_kota-dashboard.svg')}}" class="w-4 h-4" alt="">
                                <select name="penjualan" id="" class="bg-white text-xs focus:outline-none font-medium">
                                    <option selected>Depok</option>
                                    <option>Malang</option>
                                </select>
                            </div>
                            <div class="py-1.5 px-3 border-gray-200 bg-white border rounded-lg text-xs text-gray-400 flex flex-row items-center ">
                                <img src="{{asset('img/tanggal-dashboard.svg')}}" class="w-4 h-4" alt="">
                                <select name="penjualan" id="" class="bg-white text-xs focus:outline-none font-medium">
                                    <option selected>20 Januari 2022</option>
                                    <option>21 Januari 2022</option>
                                </select>
                            </div>
                        </div>
                        <table class="w-full mt-7">
                            <thead>
                                <tr class="bg-gray-100 mx-9 rounded-lg">
                                    <th class="text-left text-gray-400 text-sm py-3 pl-9 rounded-bl-lg rounded-tl-lg">
                                        Nama</th>
                                    <th class="text-left text-gray-400 text-sm py-3">Qty</th>
                                    <th class="text-left text-gray-400 text-sm py-3">Tanggal</th>
                                    <th class="text-left text-gray-400 text-sm py-3">Total</th>
                                    <th class="text-left text-gray-400 text-sm py-3 pr-9 rounded-br-lg rounded-tr-lg">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="px-7">
                                    <td class="flex flex-row items-center pl-6 py-5">
                                        <img src="{{asset('img/sambal_terasi.png')}}" alt="">
                                        <span class="font-semibold text-base">Sambal Terasi</span>
                                    </td>
                                    <td class="font-semibold">10 doz</td>
                                    <td class="text-gray-400">20 Januari 2022</td>
                                    <td class="font-semibold">Rp500,000</td>
                                    <td><span class="py-1 px-4 bg-blue-200 text-blue-700 font-bold rounded text-xs">Pending</span>
                                    </td>
                                </tr>
                                <tr class="px-7">
                                    <td class="flex flex-row items-center pl-6 py-5">
                                        <img src="{{asset('img/sambal_terasi.png')}}" alt="">
                                        <span class="font-semibold text-base">Sambal Terasi</span>
                                    </td>
                                    <td class="font-semibold">10 doz</td>
                                    <td class="text-gray-400">20 Januari 2022</td>
                                    <td class="font-semibold">Rp500,000</td>
                                    <td><span class="py-1 px-4 bg-green-200 text-green-700 font-bold rounded text-xs">Approved</span>
                                    </td>
                                </tr>
                                <tr class="px-7">
                                    <td class="flex flex-row items-center pl-6 py-5">
                                        <img src="{{asset('img/sambal_terasi.png')}}" alt="">
                                        <span class="font-semibold text-base">Sambal Terasi</span>
                                    </td>
                                    <td class="font-semibold">10 doz</td>
                                    <td class="text-gray-400">20 Januari 2022</td>
                                    <td class="font-semibold">Rp500,000</td>
                                    <td><span class="py-1 px-4 bg-amber-100 text-amber-600 font-bold rounded text-xs">Pending</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js" integrity="sha512-sW/w8s4RWTdFFSduOTGtk4isV1+190E/GghVffMA9XczdJ2MDzSzLEubKAs5h0wzgSJOQTRYyaz73L3d6RtJSg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const ctx = document.getElementById('chartLine').getContext('2d');
        var gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(252, 138, 5, 0.17)');
        gradient.addColorStop(1, 'rgba(252, 124, 5, 0)');
        var data = {

        }
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [0, 10, 11, 19, 3, 5, 2, 3],
                    backgroundColor: gradient,
                    borderColor: 'rgba(252, 124, 5, 100)',
                    borderWidth: 1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            }
        });
    </script>
    <script>
        const ctx2 = document.getElementById('chartFaktur').getContext('2d');
        const dataChartFaktur = {
            labels: ["S", "S", "R", "K", "J", "S", "M"],
            datasets: [{
                data: [65, 59, 80, 81, 56, 55, 40],
                backgroundColor: '#F26F21',
                borderColor: '#F26F21',
                borderWidth: 0,
                borderRadius: 5.7,
                borderSkipped: false
            }]
        }
        const configChartFaktur = {
            type: 'bar',
            data: dataChartFaktur,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false
                    },
                    legend: {
                        display: false,
                    },

                },
                scales: {
                    y: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: "Poppins",
                                weight: "bold"
                            }
                        }
                    }
                },
            }

        }
        const chartFaktur = new Chart(ctx2, configChartFaktur)
    </script>
</body>

</html>