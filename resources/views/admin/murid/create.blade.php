@extends('layouts.backend')

@section('content')
    <div>
        <div class="card w-100" style="padding: 2px">
            <div class="card-body">
                <h5>Input data</h5>
                <form action="{{ route('admin.murid.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="nik">NIK</label>
                            <input type="number" class="form-control @error('nik') is-invalid @enderror"
                                value="{{ old('nik') }}" name="nik" placeholder="Nomor Induk Keluarga">
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="nama">Nama Murid</label>
                            <input type="name" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" id="nama" name="nama" placeholder="Nama Murid">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="email">Email</label>
                            <input type="name" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" id="email" name="email" placeholder="Email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="jns_klmin" class="form-label">Jenis Kelamin</label>
                            <select name="jns_klmin" class="form-control @error('jns_klmin') is-invalid @enderror">
                                <option selected disabled>Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('jns_klmin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                value="{{ old('alamat') }}" id="alamat" name="alamat" placeholder="Alamat">
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="tmpt">Kota KeLahiran</label>
                            <input type="text" class="form-control @error('tmpt') is-invalid @enderror"
                                value="{{ old('tmpt') }}" id="tmpt" name="tmpt" placeholder="Tempat">
                            @error('tmpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="tgl" class="form-label">Tanggal Lahir</label>
                            <input type="text" class="form-control datepicker-here @error('tgl') is-invalid @enderror"
                                value="{{ old('tgl') }}" name="tgl" id="tgl" placeholder="Tanggal Lahir"
                                data-language='id' data-multiple-dates-separator=", " data-date-format="dd MM yyyy"
                                autocomplete="off" />
                            @error('tgl')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="tingkat" class="form-label">Tingkat</label>
                            <select class="form-select @error('tingkat') is-invalid @enderror" name="tingkat"
                                id="tingkat">
                                <option selected disabled value="" {{ old('tingkat') ? '' : 'selected' }}>
                                    Pilih Tingkat
                                </option>
                                @foreach ($tingkatEnumValues as $enumValue)
                                    <option value="{{ $enumValue }}"
                                        {{ old('tingkat') == $enumValue ? 'selected' : '' }}>
                                        {{ $enumValue }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tingkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="thn_id" class="form-label">Tahun Angkatan</label>
                            <select class="form-select @error('thn_id') is-invalid @enderror" value="{{ old('thn_id') }}"
                                name="thn_id" id="thn_id">
                                <option value="">Tahun Angkatan</option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->id }}">{{ $thn->tahun_pertama }} -
                                        {{ $thn->tahun_kedua }}
                                    </option>
                                @endforeach
                            </select>
                            @error('thn_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="rayon_id">Rayon</label>
                            <select class="form-select @error('rayon_id') is-invalid @enderror"
                                value="{{ old('rayon_id') }}" name="rayon_id">
                                <option value="">Pilih Rayon</option>
                                @foreach ($rayon as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_rayon }}</option>
                                @endforeach
                            </select>
                            @error('rayon_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-2">
                            <label for="foto">Input Foto</label>
                            <input type="file" class="form-control  @error('foto') is-invalid @enderror"
                                value="{{ old('foto') }}" name="foto" style="height: auto;">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="window.history.back();">
                    Close
                </button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
    </div>
@endsection
