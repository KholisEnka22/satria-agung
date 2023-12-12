@extends('layouts.backend')

@section('content')
    <div class="card" style="padding: 2px">
        <h5 class="card-header">{{ $title }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Murid</th>
                        <th>Nama Murid</th>
                        <th>Email</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (count($murid) > 0)
                        @foreach ($murid as $m)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $m->mrd_id }}</td>
                                <td>{{ $m->nama }}</td>
                                <td>{{ $m->email }}</td>
                                <td>{{ $m->tgl }}</td>
                                <td>{{ $m->alamat }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data di tingkat {{ $tingkat }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
