<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class HistoryController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Riwayat Pembayaran',
            'riwayatPembayaran' => Tagihan::orderBy('created_at', 'desc')
            ->get(),
        ];
        
        return view('admin.tagihan.history', $data);
    }
    public function toggleTagihanStatus($id)
    {
        $tagihan = Tagihan::findOrFail($id);

        // Ubah status pembayaran
        if ($tagihan->status === 'unpaid') {
            $tagihan->status = 'paid';
        } else {
            $tagihan->status = 'unpaid';
        }

        $tagihan->save();

        return redirect()->back()->with('message', ['type' => 'success', 'content' => 'Status pembayaran berhasil diubah.']);
    }

    public function generateStruk($history)
    {
        // Ganti dengan format dan informasi struk sesuai kebutuhan
        $html = view('struk', compact('history'))->render();

        // Ganti nama file sesuai kebutuhan
        $fileName = 'struk_pembayaran_' . $history->id . '.pdf';

        // Simpan file PDF ke storage
        PDF::loadHTML($html)->setPaper('a4')->save(storage_path("app/public/struk/{$fileName}"));

        // Update nama file struk pada model Tagihan
        $history->update(['struk' => $fileName]);
    }

    public function updateStatus($id)
    {
        $history = Tagihan::find($id);

        if (!$history) {
            abort(404, 'Data pembayaran tidak ditemukan');
        }

        // Logika update status

        // Jika status berhasil diupdate, buat struk
        if ($history->status === 'paid') {
            $this->generateStruk($history);
        }

        // Kembalikan response atau redirect sesuai kebutuhan
    }

    public function downloadStruk($id)
    {     
        // $tagihan = Tagihan::findOrFail($id);

        // // Check jika tagihan sudah dibayar
        // if ($tagihan->status !== 'paid') {
        //     return redirect()->back()->with('message', ['type' => 'error', 'content' => 'Tagihan belum dibayar.']);
        // }

        // // Generate PDF struk pembayaran
        // $pdf = app('dompdf.wrapper');
        // $pdf->loadView('struk', ['tagihan' => $tagihan]);

        // // Simpan PDF ke storage atau langsung kirim sebagai response
        // $filePath = 'struk/' . $tagihan->id . '_invoice.pdf';
        // Storage::put($filePath, $pdf->output());

        // // Unduh file
        // return response()->download(storage_path('app/' . $filePath))->deleteFileAfterSend(true);

         // Cari data tagihan dari database
        $tagihan = Tagihan::find($id);

        // Jika tagihan tidak ditemukan, berikan respons 404
        if (!$tagihan) {
            return abort(404, 'Tagihan tidak ditemukan');
        }

        // Data yang akan dikirim ke view
        $data = [
            'tagihan' => $tagihan,
        ];

        // Menggunakan view dari Laravel untuk menghasilkan HTML
        $html = view('_struk', $data)->render();

        // Contoh penggunaan library PDF (harus diinstal terlebih dahulu)
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);

        // Set paper size (A4)
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF (output)
        $dompdf->render();

        // Nama file struk
        $fileName = 'struk_' . $tagihan->id . '.pdf';

        // Lokasi penyimpanan file
        $filePath = storage_path('app/public/struk/');

        // Pastikan direktori sudah ada, jika tidak, buat direktori
        File::makeDirectory($filePath, $mode = 0777, true, true);

        // Simpan struk di storage atau lokasi yang diinginkan
        file_put_contents($filePath . $fileName, $dompdf->output());

        // Kembalikan respons untuk mengunduh file
        return response()->download($filePath . $fileName);
    }

}
