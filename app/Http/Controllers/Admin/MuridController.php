<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MuridExport;
use App\Http\Controllers\Controller;
use App\Imports\MuridImport;
use App\Models\Murid;
use App\Models\Pelatih;
use App\Models\Rayon;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class MuridController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Murid',
            'murid' => Murid::orderBy('created_at', 'desc')->get(),
            'rayon' => Rayon::all(),
            'tahun' => Tahun::all()
        ];
        return view('admin.murid.index',$data);
    }

    public function show($id)
    {
        $data = [
            'title' => 'Detail Murid',
            'murid' => Murid::find($id)
        ];
        return view('admin.murid.detail', $data);
    }
    
    public function showMuridByTingkat($tingkat)
    {
        $data = [
            'title' => 'Daftar Murid tingkat ' . $tingkat,
            'murid' => Murid::where('tingkat', $tingkat)->get(),
            'tingkat' => $tingkat
        ];

        // Kirim data ke view dan tampilkan halaman
        return view('admin.murid.tingkat', $data);
    }

    public function create()
    {
        $queryResult = DB::select("SHOW COLUMNS FROM murids WHERE Field = 'tingkat'")[0];
        preg_match('/^enum\((.*)\)$/', $queryResult->Type, $matches);
        $enumValues = explode(',', $matches[1]);
        $enumValues = array_map(fn($value) => trim($value, "'"), $enumValues);

        $data = [
            'title' => 'Tambah Murid',
            'rayon' => Rayon::all(),
            'tahun' => Tahun::all(),
            'tingkatEnumValues' => $enumValues
        ];

        return view('admin.murid.create', $data);
    }
    public function store(Request $request)
    {
        $rules = [
            'nik' => 'required',
            'nama' => 'required|unique:murids,id',
            'email' => 'required|unique:murids|email',
            'thn_id' => 'required',
            'jns_klmin' => 'required',
            'alamat' => 'required',
            'tmpt' => 'required',
            'tgl' => 'required',
            'tingkat' => 'required',
            'rayon_id' => 'required',
            'foto' => 'required|mimes:jpeg,png,jpg,gif,svg|max:5000'
        ];
        $message = [
            'nik.required' => ' NIK Tidak Boleh Kosong',
            'nama.required' => ' Nama Tidak Boleh Kosong',
            'nama.unique' => ' Nama Sudah Terdaftar',
            'email.required' => ' Email Tidak Boleh Kosong',
            'email.unique' => ' Email Sudah Terdaftar',
            'thn_id.required' => ' Tahun Ajaran Tidak Boleh Kosong',
            'jns_klmin.required' => ' Jenis Kelamin Tidak Boleh Kosong',
            'alamat.required' => ' Alamat Tidak Boleh Kosong',
            'tmpt.required' => ' Kota Kelahiran Tidak Boleh Kosong',
            'tgl.required' => ' Tanggal Lahir Tidak Boleh Kosong',
            'tingkat.required' => ' Tingkat Tidak Boleh Kosong',
            'rayon_id.required' => ' Rayon Tidak Boleh Kosong',
            'foto.required' => ' Foto Tidak Boleh Kosong',
            'foto.mimes' => ' Format Foto Tidak Didukung',
            'foto.max' => ' Ukuran File Terlalu Besar'
        ];
        $this->validate($request, $rules, $message);

        //Input Foto
        $uploadedFile = $request->file('foto');
        $filename = $request->input('nama') . '.' . $uploadedFile->getClientOriginalExtension();
        $uploadedFile->move('fotomurid/', $filename);

        //Membuat IDMurid Otomatis
        $murid = Murid::latest('id')->first();
        $rayon_id = $request->input('rayon_id');

        if ($murid == null) {
            $nubRow = 1;
        } else {
            $id = substr($murid->mrd_id, -3);
            $id = (int) $id;
            $nubRow = $id + 1;
        }

        $year = date('Y');
        $formattedNubRow = str_pad($nubRow, 3, '0', STR_PAD_LEFT);

        $mrd_id = 'PNSA' . '-' . $year . '-' . $rayon_id . "-" . $formattedNubRow;

        //Import ke table User
        $user = new User();
        $user->role = 'murid';
        $user->name = $request->nama;
        $user->email = $request->email;
        $user->password = bcrypt($request->tgl);
        $user->remember_token = Str::random(10);
        $user->save();

        Murid::create([
            'user_id' => $user->id,
            'mrd_id' => $mrd_id,
            'nik' => $request->nik,
            'thn_id' => $request->thn_id,
            'nama' => $request->nama,
            'email' => $request->email,
            'jns_klmin' => $request->jns_klmin,
            'alamat' => $request->alamat,
            'tmpt' => $request->tmpt,
            'tgl' => $request->tgl,
            'tingkat' => $request->tingkat,
            'rayon_id' => $request->rayon_id,
            'foto' => $filename,
            'is_pelatih' => 0
        ]);

        if ($murid != null && $murid->save()) {
            $messageType = 'success';
            $messageContent = 'Data berhasil disimpan.';
        } else {
            $messageType = 'danger';
            $messageContent = 'Data gagal disimpan.';
        }
        
        return redirect()->route('admin.murid')->with('message', ['type' => $messageType, 'content' => $messageContent]);     
        
    }
    public function togglePelatih($id)
    {
        $murid = Murid::findOrFail($id);
        $murid->is_pelatih = !$murid->is_pelatih;
        $murid->save();

        if ($murid->is_pelatih) {
            // Cek apakah data pelatih sudah ada
            $pelatih = Pelatih::where('mrd_id', $murid->mrd_id)->first();

            if (!$pelatih) {
                // Jika tidak ada data pelatih, buat data pelatih baru
                Pelatih::create([
                    'user_id' => $murid->user_id,
                    'mrd_id' => $murid->mrd_id,
                    'nama_pelatih' => $murid->nama,
                    'jns_klmin' => $murid->jns_klmin,
                    'nik' => $murid->nik,
                    'email' => $murid->email,
                    'tingkat' => $murid->tingkat,
                    'alamat' => $murid->alamat,
                    'tmpt' => $murid->tmpt,
                    'tgl' => $murid->tgl,
                    'rayon_id' => $murid->rayon_id,
                    'foto' => $murid->foto,
                ]);
            }

            // Update role pengguna menjadi 'pelatih'
            $user = User::findOrFail($murid->user_id);
            $user->role = 'pelatih';
            $user->save();
        } else {
            // Jika murid diubah dari pelatih, hapus data dari tabel Pelatih jika ada
            Pelatih::where('mrd_id', $murid->mrd_id)->delete();

            // Update role pengguna menjadi 'murid' jika tidak ada data pelatih lagi
            if (!Pelatih::where('user_id', $murid->user_id)->exists()) {
                $user = User::findOrFail($murid->user_id);
                $user->role = 'murid';
                $user->save();
            }
        }
        return redirect()->route('admin.murid')->with('message.type', 'success')
        ->with('message.content', 'Status pelatih berhasil diubah.');
    }
    public function edit($id)
    {
        $queryResult = DB::select("SHOW COLUMNS FROM murids WHERE Field = 'tingkat'")[0];
        preg_match('/^enum\((.*)\)$/', $queryResult->Type, $matches);
        $enumValues = explode(',', $matches[1]);
        $enumValues = array_map(fn($value) => trim($value, "'"), $enumValues);

        $data = [
            'title' => 'Edit Murid',
            'murid' => Murid::findOrFail($id),
            'rayon' => Rayon::all(),
            'tahun' => Tahun::all(),
            'tingkatEnumValues' => $enumValues

        ];
        return view('admin.murid.edit', $data);
    }
    public function update(Request $request, $id)
    {
        $murid = Murid::findOrFail($id);
        $user = User::findOrFail($murid->user_id);
        $pelatih = Pelatih::where('mrd_id', $murid->mrd_id)->first();

        $rules = [
            'nik' => 'required',
            'nama' => 'sometimes|required',
            'email' => 'sometimes|required|email',
            'thn_id' => 'required',
            'jns_klmin' => 'required',
            'alamat' => 'required',
            'tmpt' => 'required',
            'tgl' => 'required',
            'tingkat' => 'required',
            'rayon_id' => 'required',
            'foto' => 'sometimes|required|mimes:jpeg,png,jpg,gif,svg|max:5000'
        ];
        $message = [
            'nik.required' => ' NIK Tidak Boleh Kosong',
            'nama.required' => ' Nama Tidak Boleh Kosong',
            'email.required' => ' Email Tidak Boleh Kosong',
            'thn_id.required' => ' Tahun Ajaran Tidak Boleh Kosong',
            'jns_klmin.required' => ' Jenis Kelamin Tidak Boleh Kosong',
            'alamat.required' => ' Alamat Tidak Boleh Kosong',
            'tmpt.required' => ' Kota Kelahiran Tidak Boleh Kosong',
            'tgl.required' => ' Tanggal Lahir Tidak Boleh Kosong',
            'tingkat.required' => ' Tingkat Tidak Boleh Kosong',
            'rayon_id.required' => ' Rayon Tidak Boleh Kosong',
            'foto.required' => ' Foto Tidak Boleh Kosong',
            'foto.mimes' => ' Format Foto Tidak Didukung',
            'foto.max' => ' Ukuran File Terlalu Besar'
        ];
        $this->validate($request, $rules, $message);

        $murid->nik = $request->nik;
        $murid->nama = $request->nama;
        $murid->email = $request->email;
        $murid->thn_id = $request->thn_id;
        $murid->jns_klmin = $request->jns_klmin;
        $murid->alamat = $request->alamat;
        $murid->tmpt = $request->tmpt;
        $murid->tgl = $request->tgl;
        $murid->tingkat = $request->tingkat;
        $murid->rayon_id = $request->rayon_id;


        // $filename = null;
        // Periksa apakah ada file foto baru yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus foto lama dari sistem penyimpanan jika ada
            if ($murid->foto) {
                $oldFotoPath = public_path('fotomurid/' . $murid->foto);
                if (file_exists($oldFotoPath)) {
                    unlink($oldFotoPath);
                }
            }

            // Simpan foto baru dengan nama yang sama dengan nama file sebelumnya
            $uploadedFile = $request->file('foto');
            $filename = $request->nama . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move('fotomurid/', $filename);
            $murid->foto = $filename;
        }
            $murid->update();
            
            // Jika data pelatih ditemukan, update data pelatih
            if ($pelatih) {
                $pelatih->nama_pelatih = $request->nama;
                $pelatih->jns_klmin = $request->jns_klmin;
                $pelatih->nik = $request->nik;
                $pelatih->email = $request->email;
                $pelatih->tingkat = $request->tingkat;
                $pelatih->rayon_id = $request->rayon_id;
                $pelatih->alamat = $request->alamat;
                $pelatih->tmpt = $request->tmpt;
                $pelatih->tgl = $request->tgl;
                $pelatih->foto = $murid->foto;
                $pelatih->update();
            }
        

        // Edit data di tabel user
        $user->name = $request->nama;
        $user->email = $request->email;
        $user->password = bcrypt($request->tgl);
        $user->update();
        
        return redirect()->route('admin.murid')->with('message.type', 'success')
                                            ->with('message.content', 'Data berhasil diubah.');

    }

    public function destroy($id) {
        // Temukan data Murid berdasarkan ID
        $murid = Murid::find($id);
    
        // Periksa apakah data Murid ditemukan
        if ($murid) {
            // Hapus data user terlebih dahulu
            if ($murid->user) {
                $murid->user->delete();
            }
    
            // Hapus data Pelatih jika ada
            $pelatih = Pelatih::where('mrd_id', $murid->mrd_id)->first();
            if ($pelatih) {
                $pelatih->delete();
            }
    
            // Hapus foto dari sistem penyimpanan jika ada
            if ($murid->foto) {
                $fotoPath = public_path('fotomurid/' . $murid->foto);
                if (file_exists($fotoPath)) {
                    unlink($fotoPath);
                }
            }
    
            // Hapus data Murid
            $murid->delete();
    
            return redirect()->route('admin.murid')->with('message', ['type' => 'success', 'content' => 'Data murid berhasil dihapus.']);
        } else {
            return redirect()->route('admin.murid')->with('message', ['type' => 'danger', 'content' => 'Data murid tidak ditemukan.']);
        }
    }    

    public function importExcel(Request $request)
    {
        $data = $request->file('file');

        $filename = $data->getClientOriginalName();
        $data ->move('dataMurid', $filename);

        Excel::import(new MuridImport, public_path('dataMurid/'.$filename));

        return redirect()->back()->with('success', 'Data Murid Berhasil!');
       
    }
    public function exportExcel()
    {
        return Excel::download(new MuridExport, 'murids.xlsx');
    }
}
