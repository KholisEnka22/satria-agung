@extends('layouts.backend')

@section('content')
    <!-- Elemen untuk menampilkan pesan toast, awalnya tersembunyi -->
    @if (session('message'))
        <div class="bs-toast toast toast-placement-ex m-2 {{ session('message.type') === 'success' ? 'bg-success' : 'bg-danger' }}"
            id="modalMessageToast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">
                    {{ session('message.type') === 'success' ? 'Sukses' : 'Error' }}
                </div>
                <small>just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('message.content') }}
            </div>
        </div>
    @endif

    <div class="card" style="padding: 2px">
        <h5 class="card-header">Daftar Murid</h5>
        <div class="d-flex justify-content-end align-items-center mb-3">
            {{-- <form action="" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari Murid">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form> --}}
            <form class="d-flex">
                <input type="text" id="search" class="form-control" placeholder="Cari Rayon">
            </form>
            <button type="button" class="btn btn-icon btn-primary float-end ms-2 me-2" data-bs-toggle="tooltip"
                data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true" title="<span>Tambahkan Murid</span>"
                onclick="window.location.href = '{{ route('admin.murid.create') }}'">
                <span class="tf-icons bx bx-plus-medical"></span>
            </button>
            <button id="myButton" type="button" class="btn btn-icon btn-primary float-end me-2" data-bs-toggle="tooltip"
                data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true" title="<span>Import Murid</span>">
                <span class="tf-icons bx bxs-to-top"></span>
            </button>
            <button type="button" class="btn btn-icon btn-primary float-end me-2" data-bs-toggle="tooltip"
                data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true" title="<span>Export Murid</span>"
                onclick="window.location.href = '{{ route('admin.murid.export') }}'">
                <span class="tf-icons bx bxs-download"></span>
            </button>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Murid</th>
                        <th>Nama Murid</th>
                        <th>Rayon</th>
                        <th>Angkatan</th>
                        <th>Tingkat Sabuk</th>
                        <th>Pelatih</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($murid as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $m->mrd_id }}</td>
                            <td>
                                <a href="{{ route('admin.murid.detail', ['id' => $m->id]) }}" style="color: darkcyan">
                                    {{ $m->nama }}
                                </a>
                            </td>
                            <td>{{ $m->rayon->nama_rayon }}</td>
                            <td>
                                @if ($m->tahun->count() > 0)
                                    {{ $m->tahun->tahun_pertama }} - {{ $m->tahun->tahun_kedua }}
                                @endif
                            </td>
                            <td>{{ $m->tingkat }}</td>
                            <td>
                                @if (auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.murid.toggle-pelatih', ['id' => $m->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn badge {{ $m->is_pelatih ? 'bg-label-success' : 'bg-label-danger' }} me-1"
                                            onclick="return confirm('Apakah Anda yakin ingin mengubah status pelatih?')">
                                            {{ $m->is_pelatih ? 'Pelatih' : 'Tidak' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="badge {{ $m->is_pelatih ? 'bg-label-success' : 'bg-label-danger' }}">
                                        {{ $m->is_pelatih ? 'Pelatih' : 'Tidak' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.murid.edit', ['id' => $m->id]) }}">
                                            <i class="bx bx-edit-alt me-1"></i>
                                            Edit
                                        </a>
                                        <a class="dropdown-item deleted" data-id="{{ $m->id }}"
                                            data-nama="{{ $m->nama }}" href="#"><i class="bx bx-trash me-1"></i>
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
                    <form action="{{ route('admin.murid.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="tahun_pertama">Tahun Pertama</label>
                                <input type="file" class="form-control" name="file">
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
        $(document).ready(function() {
            var message = @json(session('message'));

            if (typeof message === 'object' && message !== null) {
                var messageType = message.type;
                var messageContent = message.content;

                if (messageType === 'success' || messageType === 'danger') {
                    $('#modalMessageToast .fw-semibold').text(messageType.charAt(0).toUpperCase() + messageType
                        .slice(1));
                    $('#modalMessageToast .toast-body').text(messageContent);

                    $('#modalMessageToast').toast('show');
                }
            }
        });
    </script>
    <script>
        document.getElementById('myButton').addEventListener('click', function() {
            var tooltip = new bootstrap.Tooltip(this); // Aktifkan tooltip manual
            var modal = new bootstrap.Modal(document.getElementById('basicModal')); // Aktifkan modal manual
            modal.show();
        });
    </script>

    <script>
        $('.deleted').click(function() {
            var idmurid = $(this).attr('data-id');
            var nama = $(this).attr('data-nama');

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "Untuk Menghapus " + nama + " !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = "/admin/murid/delete/" + idmurid + ""
                    Swal.fire(
                        'Deleted!',
                        'Data Berhasil Dihapus.',
                        'success',
                    )
                }
            })
        });
    </script>
@endsection
