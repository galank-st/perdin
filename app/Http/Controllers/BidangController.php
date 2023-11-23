<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BidangController extends Controller
{
    public function index(){
        $data['judul'] = 'Bidang';
        $data['sub_judul'] = 'Data Bidang';

        $role = Auth::user()->role;
        if($role == 'user'){
            return view('master.bidang', $data);
        } else {
            return view('master.bidang-admin', $data);
        }
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $opd_id = Auth::user()->opd_id;
            $data = Bidang::where('opd_id','=',$opd_id)->get();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <button class="btn btn-info btn-icon btn-sm mt-1 ubah" data-bs-toggle="modal" data-bs-target="#modalUbah" data-id="'.$row->id_bidang.'" data-bidang="'.$row->bidang.'" title="Ubah Data"><i class="las la-pencil-alt fs-3"></i></button>
                    <button class="btn btn-danger btn-icon btn-sm mt-1 hapus" title="Hapus Data" data-id="'.$row->id_bidang.'"><i class="las la-trash-alt fs-3"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create(Request $request){
        $opd_id = Auth::user()->opd_id;
        $data =  new Bidang();
        $data->bidang = $request->bidang;
        $data->opd_id = $opd_id;
        $data->save();
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }

    public function update(Request $request){
        $data =  Bidang::find($request->id_bidang);
        $data->bidang = $request->bidang;
        $data->save();
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }

    public function delete($id){
        Bidang::destroy($id);
        return redirect()->back()->with('success','Data Berhasil disimpan.');
    }

    public function bidang_byopd($opd_id)
    {
        $data = Bidang::where('opd_id','=',$opd_id)->get();
        return response()->json($data, 200);
    }
}
