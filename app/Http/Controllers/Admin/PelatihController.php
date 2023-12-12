<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use App\Models\Pelatih;
use Illuminate\Http\Request;

class PelatihController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Daftar Pelatih',
            'pelatih' => Pelatih::orderBy('created_at', 'desc')->get(),
        ];
        
        return view('admin.pelatih.index',$data);
    }
}
