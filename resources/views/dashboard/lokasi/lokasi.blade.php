@extends('dashboard.layouts.index')

@section('content')
<div class="flex flex-row justify-between items-center">
    <span class="font-bold text-lg">Lokasi</span>
    <button onclick="modal('modalTambah')" class="p-3 bg-orange-400 text-white flex flex-row items-center space-x-3 rounded-lg ">
        <img src="{{asset('img/plus-dashboard.svg')}}" alt="">
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
@endsection
@section('footer')
<div class="fixed hidden inset-0 z-40 bg-gray-600 bg-opacity-50 overflow-y-auto h-screen w-screen" id="modalTambah" onclick="modal('modalTambah')">
</div>
<div class="fixed hidden z-50 inset-0 top-20 mx-auto border w-fit h-fit shadow-lg rounded-md bg-white" id="modalTambah-content">
    <div class="p-10">
        <div class="grid grid-cols-1 space-y-8">
            <div class="col-span-1">
                <span class="text-2xl font-semibold">Tambah Lokasi</span>
            </div>
            <div class="col-span-1 grid grid-cols-2">
                <div class="col-span-1 my-auto">
                    <span class="font-medium">Region</span>
                </div>
                <div class="col-span-1 my-auto">
                    <select name="region" id="region" class="form-select text-gray-400 box-border w-full h-9 text-sm border-solid border border-gray-200 rounded focus:outline-orange-500 focus:ring-0 focus:outline-2 focus:border-gray-200 focus:outline-offset-2">
                        <option value="" selected>Pilih Region</option>
                    </select>
                </div>
            </div>
            <div class="col-span-1 grid grid-cols-2">
                <div class="col-span-1 my-auto">
                    <span class="font-medium">Nama Lokasi</span>
                </div>
                <div class="col-span-1 my-auto">
                    <input type="text" name="lokasi" id="lokasi" value="" class="box-border text-gray-400 text-sm h-9 border-solid border border-gray-200 rounded  focus:outline-orange-500 focus:ring-0 focus:outline-2 focus:border-gray-200 focus:outline-offset-2" placeholder="Input Nama">
                </div>
            </div>
            <div class="col-span-1 grid grid-cols-2">
                <div class="col-span-1 my-auto">
                    <span class="font-medium">Status</span>
                </div>
                <div class="col-span-1 my-auto">
                    <form action="#" class="flex flex-row space-x-3 items-center">
                        <div class="flex space-x-2 items-center">
                            <input type="radio" class="form-radio text-orange-500 focus:ring-0" name="status_lokasi" id="enable_status_lokasi" value="enable">
                            <label for="enable_status_lokasi">Enable</label>
                        </div>
                        <div class="flex space-x-2 items-center">
                            <input type="radio" class="form-radio text-orange-500 focus:ring-0" name="status_lokasi" id="disable_status_lokasi" value="disable">
                            <label for="disable_status_lokasi">Disable</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="px-10 py-6 flex flex-row justify-end bg-orange-50 space-x-3">
        <button onclick="modal('modalTambah')" class="text-orange-500 border border-orange-500 py-1 px-2 rounded-lg">Batal</button>
        <button class="text-white bg-orange-500 py-1 px-2 rounded-lg">Simpan</button>
    </div>
</div>

@endsection