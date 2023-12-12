@extends('layouts.backend')

@section('content')
    <div class="card" style="padding: 2px">
        <div class="card-header">
            <h4>Edit Tahun Ajaran</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tahun.update', $tahun->id) }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col mb-0">
                        <label for="tahun_pertama">Tahun Pertama</label>
                        <input type="text" class="form-control datepicker-here" placeholder="Masukkan Tahun Angkatan"
                            data-language='id' data-multiple-dates-separator=", " data-min-view="months" data-view="months"
                            data-date-format="MM yyyy" value="{{ $tahun->tahun_pertama }}" name="tahun_pertama"
                            id="tahun_pertama" autocomplete="off">
                    </div>
                    <div class="col mb-0">
                        <label for="tahun_kedua">Tahun Kedua</label>
                        <input type="text" class="form-control datepicker-here" placeholder="Masukkan Tahun Angkatan"
                            data-language='id' data-multiple-dates-separator=", " data-min-view="months" data-view="months"
                            data-date-format="MM yyyy" value="{{ $tahun->tahun_kedua }}" name="tahun_kedua" id="tahun_kedua"
                            autocomplete="off">
                    </div>
                </div>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back();">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
    </div>
    </div>
@endsection
