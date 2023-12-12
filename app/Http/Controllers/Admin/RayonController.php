<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use App\Models\Pelatih;
use App\Models\Rayon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;


class RayonController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Daftar Rayon',
            'rayon' => Rayon::orderBy('created_at', 'desc')->paginate(20),
            'ryn' => Rayon::all(),
            'count' => DB::table('murids')->count(),
            'murid' => Murid::all(),
            'pelatih' => Pelatih::all(),
        ];
        return view('admin.rayon.index',$data);
    }
    public function store(Request $request)
    {
        $rules = [
            'nama_rayon' => 'required|unique:rayons',
        ];
    
        $messages = [
            'nama_rayon.required' => 'Nama Rayon Tidak Boleh Kosong',
            'nama_rayon.unique' => 'Nama Rayon Sudah Terdaftar',
        ];
    
        $this->validate($request, $rules, $messages);
    
        
        $rayon = new Rayon();
        $rayon->nama_rayon = $request->nama_rayon;
        $rayon->id_plth = $request->id_plth;
        // dd($request->all());
        if ($rayon->save()) {
            $messageType = 'success';
            $messageContent = 'Data berhasil disimpan.';
        } else {
            $messageType = 'danger';
            $messageContent = 'Data gagal disimpan.';
        }
        
        return redirect()->route('admin.rayon')->with('message', ['type' => $messageType, 'content' => $messageContent]);     

    }
    public function update(Request $request, $id)
    {
        // Temukan rayon berdasarkan ID
        $rayon = Rayon::find($id);
    
        // Validasi input
        $rules = [
            'nama_rayon' => 'required|unique:rayons,nama_rayon,' . $rayon->id,
        ];
    
        $messages = [
            'nama_rayon.required' => 'Nama Rayon Tidak Boleh Kosong',
            'nama_rayon.unique' => 'Nama Rayon Sudah Terdaftar',
        ];
    
        $this->validate($request, $rules, $messages);
    
        // Perbarui data rayon
        $rayon->nama_rayon = $request->nama_rayon;
        $rayon->id_plth = $request->id_plth;
        // $rayon->update();
        if ($rayon->update()) {
            $messageType = 'success';
            $messageContent = 'Data berhasil diubah.';
        } else {
            $messageType = 'danger';
            $messageContent = 'Data gagal diubah.';
        }
        
        return redirect()->route('admin.rayon')->with('message', ['type' => $messageType, 'content' => $messageContent]);     
    }
    public function destroy($id){
        $rayon = Rayon::find($id);
        $rayon->delete();
        return redirect()->route('admin.rayon');
    }
    
}
