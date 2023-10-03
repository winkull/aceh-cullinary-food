<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblAdmin;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        return view('login');
    }

    public function Login(Request $request){
        if ($request->isMethod('POST')) {
            $username   = $request->username;
            $password   = $request->password;
            try{
                $usera = TblAdmin::where('idadmin', $username)->first();
                if($usera){
                    $userp = TblAdmin::where('idadmin', $username)->where('pass', md5($password))->first();
                    if($userp){
                        $request->session()->put('is_admin', true);
                        $request->session()->put('user_id', $userp->id);
                        $request->session()->put('user_idadmin', $userp->idadmin);
                        $request->session()->put('user_name', $userp->namaadmin);
                        $request->session()->put('user_address', $userp->alamatadmin);
                        $request->session()->put('user_telp', $userp->telpadmin);
    
                        $request->session()->put('dashboard_filter', 'year');
                        $request->session()->put('dashboard_filter_range_start', date('Y-m-d'));
                        $request->session()->put('dashboard_filter_range_end', date('Y-m-d'));
        
                        return redirect()->route('dashboard')->with('login_success', 'Login berhasil, Selamat datang ' . $userp->name . '!');
                    }  else {
                        return back()->with('login_fail', 'Password yang Anda masukkan salah')->withInput();
                    } 
                } else{
                    return back()->with('login_fail', 'Admin tidak ditemukan')->withInput();
                }                            
                    
            } catch (QueryException $ex) {
                return back()->with('login_fail', 'Login gagal, coba lagi!')->withInput();
            }
        } else {
            return redirect()->route('login')->with('login_fail', 'Aksi ini tidak diizinkan');
        }

    }

    public function Logout(Request $request)
    {
        $request->session()->forget('is_admin');
        $request->session()->forget('user_id');
        $request->session()->forget('user_name');
        $request->session()->forget('user_address');
        $request->session()->forget('user_telp');

        $request->session()->regenerate();
        $request->session()->flush();

        Auth::logout();
        return redirect()->route('login');
    }
}
