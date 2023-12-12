<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Bukti Pembayaran</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            /* width: 80%; */
            margin: auto;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 5px;
            overflow: hidden;
        }

        .card-header {
            background-color: #ddd;
            padding: 5px;
        }

        .card-title {
            color: #000000;
            margin-left: 15px;
        }


        .card-body {
            padding: 20px;
        }

        .table {
            width: 100%;
            border-radius: 5px;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            white-space: nowrap;
            /* Prevent text from wrapping */
        }

        .table th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Struk Pembayaran</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Murid</th>
                                    <th>Kategori Tagihan</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <td>{{ $tagihan->murid->nama }}</td>
                                    <td>{{ $tagihan->kategori->nama_tagihan }}</td>
                                    <td>Rp {{ number_format($tagihan->kategori->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            setlocale(LC_TIME, 'id_ID.utf8');
                                            echo strftime('%d %B %Y, %H:%M:%S', strtotime($tagihan->created_at));
                                        @endphp
                                    </td>
                                    <td>{{ $tagihan->status === 'unpaid' ? 'Belum Dibayar' : 'Sudah Dibayar' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 20px; text-align: right">
                        <p>Ketua Umum Satria Agung,</p>
                        <br>
                        <br>
                        <br>
                        ___________________
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
