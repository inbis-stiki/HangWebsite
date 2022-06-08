@extends('dashboard.layouts.index')

@section('content')
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
                    <select name="penjualan" id="" class="py-1.5 px-3 border-gray-200 bg-white border rounded-lg text-xs text-gray-400 ring-0 form-select`">
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
                <select name="penjualan" id="" class="bg-white text-xs focus:outline-none font-medium border-none focus:ring-0">
                    <option selected>Depok</option>
                    <option>Malang</option>
                </select>
            </div>
            <div class="py-1.5 px-3 border-gray-200 bg-white border rounded-lg text-xs text-gray-400 flex flex-row items-center ">
                <img src="{{asset('img/tanggal-dashboard.svg')}}" class="w-4 h-4" alt="">
                <select name="penjualan" id="" class="bg-white text-xs focus:outline-none font-medium border-none focus:ring-0">
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
@endsection

@section('footer')

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
@endsection