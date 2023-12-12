<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPembayaran;
use App\Models\Murid;
use App\Models\Tagihan;
use App\Models\Tahun;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Daftar Tagihan',
            'tagihan' => KategoriPembayaran::orderBy('created_at', 'desc')->paginate(20),
            'tahun' => Tahun::all(),
            'murids' => Murid::all()
        ];
        return view('admin.tagihan.index' , $data);    
    }
    public function store(Request $request) 
    {
        $request->validate([
            'nama_tagihan' => 'required',
            'jumlah' => 'required',
            'thn_id' => 'required',
        ], [
            'nama_tagihan.required' => 'Nama tagihan harus diisi.',
            'jumlah.required' => 'Jumlah tagihan harus diisi.',
            'thn_id.required' => 'Tahun harus dipilih.',
        ]);

        $kategori = new KategoriPembayaran([
            'nama_tagihan' => $request->input('nama_tagihan'),
            'jumlah' => $request->input('jumlah'),
            'thn_id' => $request->input('thn_id'),
        ]);
        $kategori->save();

        // Membuat tagihan untuk semua murid
        $murid = Murid::all();

        foreach ($murid as $m) {
            $tagihan = new Tagihan([
                'kategori_id' => $kategori->id,
                'mrd_id' => $m->id,
                'status' => 'unpaid',
            ]);
            $tagihan->save();
        }
        
        return redirect()->route('admin.tagihan')->with('message', ['type' => 'success', 'content' => 'Data berhasil disimpan.']);
    }
    public function inputManual(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'mrd_id' => 'required',
        ]);
        
        $tagihan = new Tagihan([
            'kategori_id' => $request->input('kategori_id'),
            'mrd_id' => $request->input('mrd_id'),
            'status' => 'unpaid'
        ]);
        $tagihan->save();
        
        return redirect()->route('admin.tagihan')->with('message', ['type' => 'success', 'content' => 'Data berhasil disimpan.']);
    }
}