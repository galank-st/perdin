<?php

namespace App\Http\Controllers;

use App\Models\Signer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SignerController extends Controller
{
    public function index(){
        $data['judul'] = 'Penanda Tangan';
        $data['sub_judul'] = 'Pejabat Penanda Tangan';
        $data['signer'] = DB::select("SELECT * FROM signer JOIN pegawai ON signer.pegawai_id=pegawai.id_pegawai JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan");
        $data['pegawai'] = DB::select("SELECT * FROM pegawai JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan WHERE jabatan_id != '6' AND jabatan_id != '5' ORDER BY id_jabatan");
        $data['pangkat'] = $data['signer'][0]->pangkat;


        $role = Auth::user()->role;
        if($role == 'user'){
            return redirect(route('dashboard'));
        } else {
            return view('master.signer-admin', $data);
        }
    }

    public function create(Request $request){
        $data = Signer::find(1);
        $data->pegawai_id = $request->pegawai_id;
        $data->pangkat = $request->pangkat;
        $data->save();
        return redirect(route('signer'))->with('success', 'Data berhasil disimpan.');
    }
}
