@extends('layouts.backend')
@section('content')
    <div class="card" style="padding: 2px">
        <h5 class="card-header">{{ $title }}
            @if (auth()->user()->role === 'admin')
                <button id="myButton" type="button" class="btn btn-icon btn-primary float-end" data-bs-placement="bottom"
                    data-bs-html="true" title="Tambah Tahun Angkatan">
                    <span class="tf-icons bx bx-plus-medical"></span>
                </button>
            @endif
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Angkatan</th>
                        <th>Jumlah Murid</th>
                        @if (auth()->user()->role === 'admin')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($tahun as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $t->tahun_pertama }} - {{ $t->tahun_kedua }}
                            </td>
                            <td>
                                @php
                                    $jumlahMurid = $murid->where('thn_id', $t->id)->count();
                                @endphp

                                @if ($jumlahMurid > 0)
                                    {{ $jumlahMurid }}
                                @else
                                    <span class="badge bg-label-danger me-1">Tidak ada murid</span>
                                @endif
                            </td>
                            @if (auth()->user()->role === 'admin')
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.tahun.edit', ['id' => $t->id]) }}">
                                                <i class="bx bx-edit-alt me-1"></i>
                                                Edit
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0);"><i
                                                    class="bx bx-trash me-1"></i>
                                                Delete</a>
                                        </div>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!--/ Hoverable Table rows -->
        <!-- Modal -->
        <div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="basicModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Tambah Tahun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.tahun.store') }}" method="POST">
                            @csrf
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label for="tahun_pertama">Tahun Pertama</label>
                                    <input type="text" class="form-control datepicker-here"
                                        placeholder="Masukkan Tahun Angkatan" data-language='id'
                                        data-multiple-dates-separator=", " data-min-view="months" data-view="months"
                                        data-date-format="MM yyyy" value="{{ old('tahun_pertama') }}" name="tahun_pertama"
                                        id="tahun_pertama" autocomplete="off">
                                </div>
                                <div class="col mb-0">
                                    <label for="tahun_kedua">Tahun Kedua</label>
                                    <input type="text" class="form-control datepicker-here"
                                        placeholder="Masukkan Tahun Angkatan" data-language='id'
                                        data-multiple-dates-separator=", " data-min-view="months" data-view="months"
                                        data-date-format="MM yyyy" value="{{ old('tahun_kedua') }}" name="tahun_kedua"
                                        id="tahun_kedua" autocomplete="off">
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- End Modal --}}
    @endsection

    @section('footer')
        <script>
            document.getElementById('myButton').addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('basicModal')); // Aktifkan modal manual
                modal.show();
            });
        </script>
    @endsection
