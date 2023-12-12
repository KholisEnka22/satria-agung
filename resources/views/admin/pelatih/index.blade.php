@extends('layouts.backend')

@section('content')
    <div class="card" style="padding: 2px">
        <h5 class="card-header">{{ $title }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pelatih</th>
                        <th>Nama Pelatih</th>
                        <th>Tingkat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (count($pelatih) > 0)
                        @foreach ($pelatih as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->mrd_id }}</td>
                                <td>{{ $p->nama_pelatih }}</td>
                                <td>{{ $p->tingkat }}</td>
                                <td><span class="badge bg-label-success me-1">Pelatih</span></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">
                                Tidak ada data pelatih
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
