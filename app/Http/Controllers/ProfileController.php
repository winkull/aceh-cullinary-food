<?php

namespace App\Http\Controllers;

use App\Models\TblAdmin;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request){
        $id = $request->session()->get('user_id');
        $data = (object)array(
            'title'     => 'PROFILE',
            'menu'      => 'profile',
            'select'    => (object) array(
                'user'  => TblAdmin::where('id',$id)->first())
        );
        return view('profile.profile', compact('data'));
    }

    public function resetPassword(Request $request){
        if ($request->isMethod('POST')){
            try{
                if ($request->pass1== $request->pass2) {
                    $data              = TblAdmin::where('id', $request->id)->first();
                    $data->pass        = md5($request->pass1);
                    $data->save();

                    return redirect()->back()->with("success", "Berhasil ubah password Admin <strong>" . $data->namaadmin . "</strong>");                                    
                }else{
                    return redirect()->back()->with("fail", "Kolom Password Baru dan Konfirmasi Password tidak sama, Silahkan coba lagi!");
                }               
                
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal ubah password " . $ex->getMessage())->withInput();
            } 
        }else
        {
            return abort(405);
        }
    }
}
