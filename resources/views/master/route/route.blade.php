@extends('template.header')
@extends('template.sidebar')

<div class="content-body">
    <div class="container-fluid">

        @if ($errors->any())
        <div class="alert alert-danger" style="margin-top: 1rem;">{{ $errors->first() }}</div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('master/rute/store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="ID_USER">Nama User</label>
                                <select name="ID_USER" id="ID_USER" class="form-control">
                                    @foreach($users as $user)
                                    <option value="{{ $user->ID_USER }}">{{ $user->NAME_USER }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="WEEK">Minggu</label>
                                <input type="number" name="WEEK" id="WEEK" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="ID_SHOP">Nama Toko</label>
                                <select name="ID_SHOP[]" id="ID_SHOP" class="form-control select2-toko-rute" multiple></select>
                            </div>
                            <div class="form-group">
                                <label for="ROUTE_GROUP">Rute Grup</label>
                                <input type="number" name="ROUTE_GROUP" id="ROUTE_GROUP" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@extends('template.footer')