<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunController extends Controller
{
    public function index()
    {        
        $data = [
            'title' => 'Daftar Tahun Ajaran',
            'tahun' => Tahun::all(),
            'murid' => Murid::all(),
            'count' => DB::table('murids')->count()
        ];
        return view('admin.tahun.index',$data);
    }
    public function store(Request $request)
    {
        $rules = [
            'tahun_pertama' => 'required',
            'tahun_kedua' => 'required'
        ];
        $message = [
            'tahun_pertama.required' => 'Tahun Tidak Boleh Kosong',
            'tahun_kedua.required' => 'Tahun Tidak Boleh Kosong'
        ];
        $this->validate($request, $rules, $message);

        Tahun::create(
            [
                'tahun_pertama' => $request->tahun_pertama,
                'tahun_kedua' => $request->tahun_kedua,
            ]
        );
        return redirect()->route('admin.tahun');
    }
    public function edit($id)
    {
        $data = [
            'tahun' => Tahun::find($id),
            'title' => 'Edit Tahun'
        ];
        return view('admin.tahun.edit',$data);
    }
    public function update(Request $request, $id)
    {
        $tahun = Tahun::find($id);
        $rules = [
            'tahun_pertama' => 'required',
            'tahun_kedua' => 'required'
        ];
        $message = [
            'tahun_pertama.required' => 'Tahun Tidak Boleh Kosong',
            'tahun_kedua.required' => 'Tahun Tidak Boleh Kosong'
        ];

        $this->validate($request, $rules, $message);
        
        $tahun->tahun_pertama = $request->tahun_pertama;
        $tahun->tahun_kedua = $request->tahun_kedua;
        $tahun->update();
        
        return redirect()->route('admin.tahun');
    }
}