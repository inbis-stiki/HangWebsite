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
                        <form action="{{ url('routes/store') }}" method="POST">
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
                                <select name="ID_SHOP" id="ID_SHOP" class="form-control select2">
                                    @foreach($shops as $shop)
                                        <option value="{{ $shop->ID_SHOP }}">{{ $shop->NAME_SHOP }}</option>
                                    @endforeach
                                </select>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#ID_SHOP').select2({
            ajax: {
                url: "{{ url('master/rute/search-shops') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.NAME_SHOP,
                                id: item.ID_SHOP
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    });
</script>
