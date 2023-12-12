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

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{ $title }}</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            <input type="text" width="20" class="form-control" style="width: 50px;" placeholder="No"
                                readonly>
                        </th>
                        <th>
                            <input type="text" class="form-control" placeholder="Nama Murid">
                        </th>
                        <th>
                            <input type="text" class="form-control" placeholder="Nama Tagihan">
                        </th>
                        <th>
                            <input type="text" class="form-control" placeholder="Jumlah Tagihan" readonly>
                        </th>
                        <th>
                            <input type="text" class="form-control" placeholder="Status">
                        </th>
                        <th>
                            <input type="text" class="form-control" placeholder="Aksi" readonly>
                        </th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if (count($riwayatPembayaran) > 0)
                        @foreach ($riwayatPembayaran as $history)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $history->murid->nama }}</td>
                                <td>{{ $history->kategori->nama_tagihan }}</td>
                                <td>{{ 'Rp ' . number_format($history->kategori->jumlah, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('admin.tagihan.toggle-status', ['id' => $history->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn badge {{ $history->status === 'unpaid' ? 'bg-label-danger' : 'bg-label-success' }}"
                                            onclick="return confirm('Apakah Anda yakin ingin mengubah status pembayaran?')">
                                            {{ $history->status === 'unpaid' ? 'Belum Dibayar' : 'Sudah Dibayar' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if ($history->status === 'paid')
                                        <a href="{{ route('admin.tagihan.download', ['id' => $history->id]) }}"
                                            class="btn btn-success btn-sm">Download Struk</a>
                                    @else
                                        {{-- Tidak menampilkan tombol jika status belum dibayar --}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Tidak Ada History Pembayaran Saat Ini</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('footer')
    <script>
        $(document).ready(function() {
            // Ketika input di kolom pencarian berubah
            $('thead input').on('input', function() {
                const columnIndex = $(this).closest('th').index(); // Indeks kolom input

                // Nilai pencarian dalam kolom ini
                const searchValue = $(this).val().toLowerCase();

                // Loop melalui setiap baris dalam tbody
                $('tbody tr').each(function() {
                    const cellValue = $(this).find('td').eq(columnIndex).text().toLowerCase();

                    // Cek apakah nilai dalam kolom cocok dengan nilai pencarian
                    if (cellValue.includes(searchValue)) {
                        $(this).show(); // Menampilkan baris jika cocok
                    } else {
                        $(this).hide(); // Menyembunyikan baris jika tidak cocok
                    }
                });
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
