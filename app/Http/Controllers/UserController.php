<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(){
        $data['title'] = "User";
        $data['sub_title'] = "Data User";
        return view('data-user', $data);
    }

    public function create(Request $request){    
        if($request->id){
            $data = User::find($request->id);
            $data->name     = $request->name;
            $data->username = $request->username;
            $data->email    = $request->email;
            if($request->password){
                $data->password = Hash::make($request->password);
            }
            $data->role     = $request->role;
            $data->created_at = now();
            $data->save();
        } else {
            $data = new User();
            $data->name     = $request->name;
            $data->username = $request->username;
            $data->email    = $request->email;
            $data->password = Hash::make($request->password);
            $data->role     = $request->role;
            $data->created_at = now();
            $data->save();
        } 
        
        // return $data;         
        if ($data) {
            return response()->json([
                'status'     => 'success']);
        } else {
            return response()->json([
                'status' => 'error']);
        }
    }

    public function cek_username($cek){
        $data = DB::select("SELECT * FROM users WHERE username = '$cek' OR email = '$cek'");
        if ($data){
            return response()->json($data, 200);

        }
    }



    public function get_user(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select("SELECT * FROM users");
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <button class="btn btn-icon btn-success ubah" data-bs-toggle="modal" data-bs-target="#modalUbah" 
                    data-id="'.$row->id_user.'" 
                    data-name="'.$row->name.'"
                    data-username="'.$row->username.'"
                    data-email="'.$row->email.'"
                    data-password="'.$row->password.'"
                    data-role="'.$row->role.'"
                    ><i class="ki-outline ki-pencil text-white fs-3"></i></button> 
                    <button class="btn btn-icon btn-danger hapus" data-bs-toggle="modal" data-id="'.$row->id_user.'"><i class="ki-outline ki-trash text-white fs-3"></i></button>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        User::destroy($id);
        // return "hmmmm";
        return response()->json(['status' => 'success']);
    }

}
