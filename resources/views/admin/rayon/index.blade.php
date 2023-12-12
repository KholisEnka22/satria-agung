@extends('layouts.backend')

@section('content')
    <!-- Elemen untuk menampilkan pesan toast, awalnya tersembunyi -->
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

    <div class="card" style="padding: 2px">
        <h5 class="card-header">{{ $title }}</h5>
        <div class="d-flex justify-content-end align-items-center mb-3">
            <form class="d-flex">
                <input type="text" id="search" class="form-control me-2" placeholder="Cari Rayon">
            </form>
            @if (auth()->user()->role === 'admin')
                <button id="myButton" type="button" class="btn btn-icon btn-primary float-end me-3"
                    data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true"
                    data-bs-original-title="<span>Tambahkan Rayon</span>">
                    <span class="tf-icons bx bx-plus-medical"></span>
                </button>
            @endif
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Rayon</th>
                        <th>Nama Pelatih</th>
                        <th>Jumlah Murid</th>
                        @if (auth()->user()->role === 'admin')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($rayon as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $r->nama_rayon }}</td>
                            <td>
                                @if ($r->pelatih)
                                    {{ $r->pelatih->nama_pelatih }}
                                @else
                                    <span class="badge rounded-pill bg-danger me-1">Tidak ada Pelatih</span>
                                @endif
                            </td>
                            {{-- <td>{{ $murid->where('rayon_id', $r->id)->count() }}</td> --}}
                            <td>
                                @php
                                    $jumlahMurid = $murid->where('rayon_id', $r->id)->count();
                                @endphp

                                @if ($jumlahMurid > 0)
                                    {{ $jumlahMurid }}
                                @else
                                    <span class="badge rounded-pill bg-danger me-1">Tidak ada murid</span>
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
                                            <a class="dropdown-item" href="#"data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $r->id }}">
                                                <i class="bx bx-edit-alt me-1"></i>
                                                Edit
                                            </a>
                                            <a class="dropdown-item deleted" data-id="{{ $r->id }}"
                                                data-nama="{{ $r->nama_rayon }}" href="#"><i
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
            <!-- Basic Pagination -->
            <nav aria-label="Page navigation" style="margin-top: 10px">
                <ul class="pagination justify-content-center">
                    @if ($rayon->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&laquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $rayon->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif

                    @foreach ($rayon->getUrlRange(1, $rayon->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $rayon->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if ($rayon->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $rayon->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&raquo;</span>
                        </li>
                    @endif
                </ul>
            </nav>
            <!--/ Basic Pagination -->
        </div>
    </div>
    <!--/ Hoverable Table rows -->
    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="basicModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Tambah Rayon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.rayon.store') }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="nama_rayon">Nama Rayon</label>
                                <input type="text" class="form-control @error('nama_rayon') is-invalid @enderror"
                                    value="{{ old('nama_rayon') }}" name="nama_rayon" id="nama_rayon"
                                    placeholder="Masukkan Nama Rayon">
                                @error('nama_rayon')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col mb-0">
                                <label for="id_plth">Nama Pelatih</label>
                                <select class="form-select" name="id_plth" id="id_plth">
                                    <option value="">Pilih Pelatih</option>
                                    @foreach ($pelatih as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_pelatih }}</option>
                                    @endforeach
                                </select>
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
    {{-- endModal --}}

    <!-- Modal Edit-->
    @foreach ($ryn as $r)
        <div class="modal fade" id="editModal{{ $r->id }}" tabindex="-1" aria-labelledby="basicModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModal{{ $r->id }}">Edit Rayon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.rayon.update', $r->id) }}" method="POST">
                            @csrf
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label for="nama_rayon">Nama Rayon</label>
                                    <input type="text" class="form-control @error('nama_rayon') is-invalid @enderror"
                                        value="{{ $r->nama_rayon }}" name="nama_rayon" id="nama_rayon"
                                        placeholder="Masukkan Nama Rayon">
                                    @error('nama_rayon')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col mb-0">
                                    <label for="id_plth">Nama Pelatih</label>
                                    <select class="form-select" name="id_plth" id="id_plth">
                                        <option value="">Pilih Pelatih</option>
                                        @foreach ($pelatih as $p)
                                            <option value="{{ $p->id }}"
                                                {{ $r->pelatih && $r->pelatih->id == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_pelatih }}
                                            </option>
                                        @endforeach
                                    </select>
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
    @endforeach
    {{-- endModal --}}
@endsection

@section('footer')
    <script>
        document.getElementById('myButton').addEventListener('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('basicModal')); // Aktifkan modal manual
            modal.show();
        });
    </script>

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
        $('.deleted').click(function() {
            var idrayon = $(this).attr('data-id');
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
                    window.location = "/admin/rayon/delete/" + idrayon + ""
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
