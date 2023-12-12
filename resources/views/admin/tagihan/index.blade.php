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
        <h5 class="card-header">{{ $title }}
            <button id="myButtonManual" type="button" class="btn btn-primary float-end" data-bs-placement="bottom"
                data-bs-toggle="tooltip" data-bs-html="true" title="Input Tagihan Ke Murid">Input
                Manual</button>
            <button id="myButton" type="button" class="btn btn-icon btn-primary float-end me-2" data-bs-placement="left"
                data-bs-toggle="tooltip" data-bs-html="true" title="Tambah Tagihan">
                <span class="tf-icons bx bx-plus-medical"></span>
            </button>
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tagihan</th>
                        <th>Jumlah Tagihan</th>
                        <th>Tahun</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (count($tagihan) > 0)
                        @foreach ($tagihan as $tag)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tag->nama_tagihan }}</td>
                                <td>{{ 'Rp ' . number_format($tag->jumlah, 0, ',', '.') }}</td>
                                <td>
                                    @if ($tag->tahun->count() > 0)
                                        {{ $tag->tahun->tahun_pertama }} - {{ $tag->tahun->tahun_kedua }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada tagihan saat ini</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="basicModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Tambah Tagihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.tagihan.store') }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="nama_tagihan">Nama Tagihan</label>
                                <input type="text" class="form-control" value="{{ old('nama_tagihan') }}"
                                    name="nama_tagihan" id="nama_tagihan" placeholder="Nama Tagihan" required>
                            </div>
                            <div class="col mb-0">
                                <label for="jumlah">Jumlah</label>
                                <input type="text" class="form-control" value="{{ old('jumlah') }}" name="jumlah"
                                    id="jumlah" placeholder="Jumlah" required>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label for="thn_id" class="form-label">Tahun Angkatan</label>
                                    <select class="form-select" value="{{ old('thn_id') }}" name="thn_id" id="thn_id">
                                        <option value="">Tahun Angkatan</option>
                                        @foreach ($tahun as $thn)
                                            <option value="{{ $thn->id }}">{{ $thn->tahun_pertama }} -
                                                {{ $thn->tahun_kedua }}</option>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
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

    {{-- Modal Input Manual --}}
    <div class="modal fade" id="inputManualModal" tabindex="-1" aria-labelledby="inputManualModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inputManualModalLabel">Input Tagihan Ke Murid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.manualInput') }}">
                        @csrf
                        <div class="form-group">
                            <label for="mrd_id">Pilih Murid:</label>
                            <select name="mrd_id" id="mrd_id" class="form-select" required>
                                <option value="">Pilih Murid</option>
                                @foreach ($murids as $murid)
                                    <option value="{{ $murid->id }}">{{ $murid->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Pilih Kategori Tagihan:</label>
                            <select name="kategori_id" id="kategori_id" class="form-select" required>
                                <option value="">Pilih Tagihan</option>
                                @foreach ($tagihan as $tag)
                                    <option value="{{ $tag->id }}" data-jumlah="{{ $tag->jumlah }}">
                                        {{ $tag->nama_tagihan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah Tagihan:</label>
                            <input type="text" name="jumlah" id="jumlah_otomatis" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="status">Status Pembayaran:</label>
                            <input type="text" name="status" id="status" class="form-control">
                            <small class="text-info">*paid atau unpaid</small>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
    <script>
        document.getElementById('myButtonManual').addEventListener('click', function() {
            var tooltip = new bootstrap.Tooltip(this); // Aktifkan tooltip manual
            var modal = new bootstrap.Modal(document.getElementById('inputManualModal')); // Aktifkan modal manual
            modal.show();
        });
    </script>
    <script>
        // Fungsi untuk mengubah angka menjadi format Rupiah
        function formatRupiah(angka) {
            var number_string = angka.toString();
            var split = number_string.split(',');
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{1,3}/gi);

            // Tambahkan titik sebagai pemisah ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const kategoriSelect = document.getElementById("kategori_id");
            const jumlahInput = document.getElementById("jumlah_otomatis");

            // Menggunakan event listener untuk mendeteksi perubahan dalam select
            kategoriSelect.addEventListener("change", function() {
                const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];

                // Mengisi nilai jumlah dengan nilai dari data-jumlah pada opsi yang dipilih
                const jumlah = selectedOption.getAttribute("data-jumlah") || "";

                // Format jumlah sebagai Rupiah dan isi input
                jumlahInput.value = formatRupiah(jumlah);
            });
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
@endsection
