<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class JabatanController extends Controller
{
    public function index(){
        $data['judul'] = 'Jabatan';
        $data['sub_judul'] = 'Data Jabatan';

        $role = Auth::user()->role;
        if($role == 'user'){
            return view('master.jabatan', $data);
        } else {
            return view('master.jabatan-admin', $data);
        }
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Jabatan::get();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <button class="btn btn-info btn-icon btn-sm mt-1 ubah" data-bs-toggle="modal" data-bs-target="#modalUbah" data-id="'.$row->id_jabatan.'" data-jabatan="'.$row->jabatan.'" title="Ubah Data"><i class="las la-pencil-alt fs-3"></i></button>
                    <button class="btn btn-danger btn-icon btn-sm mt-1 hapus" title="Hapus Data" data-id="'.$row->id_jabatan.'"><i class="las la-trash-alt fs-3"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create(Request $request){
        $data =  new Jabatan();
        $data->jabatan = $request->jabatan;
        $data->save();
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }

    public function update(Request $request){
        $data =  Jabatan::find($request->id_jabatan);
        $data->jabatan = $request->jabatan;
        $data->save();
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }

    public function delete($id){
        Jabatan::destroy($id);
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }
}
