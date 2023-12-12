@extends('layouts.backend')

@section('content')
    <div class="col-md">
        @if ($murid)
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img class="card-img card-img-left" src="{{ asset('fotomurid/' . $murid->foto) }}"
                            alt="{{ $murid->nama }}" width="50" />
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h4 class="card-title">{{ $murid->nama }}</h4>
                            <ul class="list-group">
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="bx bx-id-card me-2"></i>
                                    {{ $murid->mrd_id }}
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="bx bx-building-house me-2"></i>
                                    {{ $murid->rayon->nama_rayon }}
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="bx bx-shield-alt me-2"></i>
                                    {{ $murid->tingkat }}
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="bx bx-calendar me-2"></i>
                                    {{ $murid->tmpt }}, {{ $murid->tgl }}
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="bx bx-trophy me-2"></i>
                                    <ul>
                                        <li>Juara 1 PosPeda Tingkat Kabupaten</li>
                                        <li>Juara 2 PosPeda Tongkat Provinsi</li>
                                        <li>Juara 1 Bupati Cup Pasuruan</li>
                                    </ul>
                                </li>
                            </ul>
                            <p class="card-text">
                                <small class="text-muted">
                                    Last updated {{ $murid->updated_at->diffForHumans() }}.
                                </small>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        @else
            <p>Data tidak ditemukan.</p>
        @endif
    </div>
@endsection
