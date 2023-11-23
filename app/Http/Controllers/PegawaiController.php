<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PegawaiController extends Controller
{
    public function index(){
        $opd_id = Auth::user()->opd_id;
        $data['judul'] = 'Pegawai';
        $data['sub_judul'] = 'Data Pegawai Negeri Sipil (PNS)';
        $data['bidang'] = Bidang::where('opd_id','=', $opd_id)->get();
        $data['jabatan'] = Jabatan::get();
        $data['opd'] = Opd::get();
        $role = Auth::user()->role;
        if($role == 'user'){
            return view('pegawai.data', $data);
        } elseif($role == 'admin') {
            return view('pegawai.data-admin', $data);
        } elseif($role == 'super') {
            return view('super.data-pegawai', $data);
        } 
    }

    public function index_byopd($opd_id){
        $data['judul'] = 'Pegawai';
        $data['sub_judul'] = 'Data Pegawai Negeri Sipil (PNS)';
        $data['bidang'] = Bidang::where('opd_id','=', $opd_id)->get();
        $data['jabatan'] = Jabatan::get();
        $data['opd'] = Opd::get();
        $data['opd_id'] = $opd_id;
        $role = Auth::user()->role;

        return view('super.data-pegawai', $data);
        
    }

    public function data(Request $request)
    {
        $opd_id = Auth::user()->opd_id;

        if ($request->ajax()) {
            $role = Auth::user()->role;
            if($role == 'user'){
                $data = DB::select("SELECT * FROM pegawai JOIN bidang ON pegawai.bidang_id=bidang.id_bidang JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan WHERE pegawai.opd_id='$opd_id' ORDER BY id_jabatan");
            } else {
                $data = DB::select("SELECT * FROM pegawai JOIN bidang ON pegawai.bidang_id=bidang.id_bidang JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan WHERE pegawai.opd_id='$opd_id' ORDER BY id_pegawai DESC");
            }
            
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <button class="btn btn-info btn-icon btn-sm mt-1 ubah" data-bs-toggle="modal" data-bs-target="#modalUbah" data-id="'.$row->id_pegawai.'" data-nip="'.$row->nip.'"  data-nama="'.$row->nama.'"  data-bidang_id="'.$row->bidang_id.'"  data-jabatan_id="'.$row->jabatan_id.'" title="Ubah Data"><i class="las la-pencil-alt fs-3"></i></button>
                    <button class="btn btn-danger btn-icon btn-sm mt-1 hapus" title="Hapus Data" data-id="'.$row->id_pegawai.'"><i class="las la-trash-alt fs-3"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function data_byopd(Request $request, $opd_id)
    {
        if ($request->ajax()) {
            $role = Auth::user()->role;
            if($role == 'user'){
                $data = DB::select("SELECT * FROM pegawai JOIN bidang ON pegawai.bidang_id=bidang.id_bidang JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan WHERE pegawai.opd_id='$opd_id' ORDER BY id_jabatan");
            } else {
                $data = DB::select("SELECT * FROM pegawai JOIN bidang ON pegawai.bidang_id=bidang.id_bidang JOIN jabatan ON pegawai.jabatan_id=jabatan.id_jabatan WHERE pegawai.opd_id='$opd_id' ORDER BY id_pegawai DESC");
            }
            
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <button class="btn btn-info btn-icon btn-sm mt-1 ubah" data-bs-toggle="modal" data-bs-target="#modalUbah" data-id="'.$row->id_pegawai.'" data-nip="'.$row->nip.'"  data-nama="'.$row->nama.'"  data-bidang_id="'.$row->bidang_id.'"  data-jabatan_id="'.$row->jabatan_id.'" title="Ubah Data"><i class="las la-pencil-alt fs-3"></i></button>
                    <button class="btn btn-danger btn-icon btn-sm mt-1 hapus" title="Hapus Data" data-id="'.$row->id_pegawai.'"><i class="las la-trash-alt fs-3"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create(Request $request){
        $opd_id = Auth::user()->opd_id;

        $data =  new Pegawai();
        $data->nip = $request->nip;
        $data->nama = $request->nama;
        $data->bidang_id = $request->bidang_id;
        $data->jabatan_id = $request->jabatan_id;
        $data->opd_id = $opd_id;
        $data->save();
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }

    public function create_byopd(Request $request){
        
        $pegawai = Pegawai::where('nip','=', $request->nip)->get();
        // return $pegawai;
        if ($pegawai) {
            return redirect()->back()->with('error','NIP udah digunakan.');
        }

        $data =  new Pegawai();
        $data->nip = $request->nip;
        $data->nama = $request->nama;
        $data->bidang_id = $request->bidang_id;
        $data->jabatan_id = $request->jabatan_id;
        $data->opd_id = $request->opd_id;
        $data->save();
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }

    public function update(Request $request){
        $data =  Pegawai::find($request->id_pegawai);
        $data->nip = $request->nip;
        $data->nama = $request->nama;
        $data->bidang_id = $request->bidang_id;
        $data->jabatan_id = $request->jabatan_id;
        $data->save();
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }

    public function delete($id){
        Pegawai::destroy($id);
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }
}
