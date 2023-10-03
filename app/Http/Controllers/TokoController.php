<?php

namespace App\Http\Controllers;

use App\Models\TblToko;
use App\Models\TblMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class TokoController extends Controller
{
    public function index(){
        $data = (object)array(
            'title'     => 'TENANT',
            'menu'      => 'tenant',       
        );
        return view('tenant.tenant', compact('data'));
    }

    public function tenantCreate(){
        $data = (object)array(
            'title'     => 'TAMBAH TENANT',
            'menu'      => 'tenant',
        );

        return view('tenant.add', compact('data'));
    }

    public function tenantInsert(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblToko::where('kodetoko', $request->kode)->first();
                if($dataExists)
                {
                    return back()->with("fail", "id toko <strong>" . $request->kode . "</strong> sudah terdaftar")->withInput();
                }

                $data             = new TblToko();
                $data->kodetoko   = $request->kode;
                $data->nama       = $request->nama;
                $data->pemilik    = $request->pemilik;
                $data->alamat     = $request->alamat;
                $data->telp       = $request->telepon;
                $data->email      = $request->email;
                $data->kota       = $request->kota;
                $data->ket        = $request->keterangan;
                $data->kelompok   = $request->kelompok;
                $data->pass       = md5($request->password);
                $data->longitude  = $request->longitude;
                $data->latitude   = $request->latitude;

                if ($request->deskripsi == '') {
                    $data->deskripsi = '-';
                }else{
                    $data->deskripsi  = $request->deskripsi;
                }

                if ($request->rating == '') {
                    $data->rating = '0';
                }else{
                    $data->rating     = $request->rating;
                }

                if ($request->catatan == '') {
                    $data->catatan    = '-';
                }else{
                    $data->catatan    = $request->catatan;
                }

                if ($request->file('foto') == '') {
                    $data->gambar = 'NULL';
                }else{
                    if ($request->hasfile('foto')) {
                        $size = $request->file('foto')->getSize();
                        if ($size<=2097152) {
                            $time       = Carbon::now();
                            $file = $request->file('foto');
                            $extension = $file->getClientOriginalExtension();
                            $filename = "IMG-".Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . '.' . $extension;
                            $file->move('uploads/tenants', $filename);
                                $data->gambar = $filename;                       
                        }else{
                            return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                        }                    
                    }else{
                        return back()->with("fail", "Gagal menyimpan gambar")->withInput();
                    }
                }
                
                $data->save();                

                return redirect()->route("tenant")->with("success", "Berhasil tambah data toko <strong>" . $data->nama . "</strong>");
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal tambah data tenant " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function tenantData(Request $request){
        $columns = array(
            1 => 'kodetoko',
            2 => 'nama',
            3 => 'telp',
            4 => 'longitude',
            5 => 'latitude',
            6 => 'gambar',
        );

        $totalData = TblToko::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tbltoko')
                    ->selectRaw('*')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->orderBy('kodetoko','asc')
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tbltoko')
                    ->selectRaw('*')
                    ->where(function ($query) use ($search){
                        $query->orWhere('kodetoko','LIKE',"%{$search}%")
                            ->orWhere('nama','LIKE',"%{$search}%")
                            ->orWhere('telp','LIKE',"%{$search}%")
                            ->orWhere('longitude','LIKE',"%{$search}%")
                            ->orWhere('latitude','LIKE',"%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

            $totalFiltered = DB::table('tbltoko')
                            ->selectRaw('*')
                            ->where(function ($query) use ($search){
                                $query->orWhere('kodetoko','LIKE',"%{$search}%")
                                    ->orWhere('nama','LIKE',"%{$search}%")
                                    ->orWhere('telp','LIKE',"%{$search}%")
                                    ->orWhere('longitude','LIKE',"%{$search}%")
                                    ->orWhere('latitude','LIKE',"%{$search}%");
                            })
                            ->count();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = $start + 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['id']          = [$item->kodetoko . ',' . $item->nama . ',' . route("tenant.edit", ['id' => $item->kodetoko]) . ',' . route("tenant.delete", ['id' => $item->kodetoko]) . ',' . route("menu", ['id' => $item->kodetoko])];
                $nestedData['kode']        = $item->kodetoko;
                $nestedData['nama']        = $item->nama;
                $nestedData['ket']         = $item->ket == 'Buka' ? '<div class="text-center"><small><label class="bg-success text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Buka</label></small></div>' : ( $item->ket == 'Tutup' ? '<div class="text-center"><small><label class="bg-warning text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Tutup</label></small></div>' : '<div class="text-center"><small><label class="bg-danger text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Destroyed</label></small></div>' );
                $nestedData['telepon']     = $item->telp;
                $nestedData['longitude']   = $item->longitude;
                $nestedData['latitude']    = $item->latitude;
                $nestedData['gambar']      = $item->gambar == 'NULL' ? '' : '<div class="col-md-4 col-sm-1 grid-element">
                                                    <div class="tile-basic tile-basic-hover-shadow tile-basic-bordered">
                                                        <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModalCenter'.$item->kodetoko.'">
                                                            <span class="fad fa-image"></span> Lihat
                                                        </a>                                                      
                                                    </div>
                                               </div>
                                               <div class="modal fade" id="exampleModalCenter'.$item->kodetoko.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Preview</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img width="100%" src="'.asset("uploads/tenants"). "/" .$item->gambar.'">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';
                $nestedData['catatan']     = $item->catatan;

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return response()->json($json_data);
    }

    public function tenantEdit(Request $request){
        try{
            $id = $request->id;
            $data = (object)array(
                'title'     => 'EDIT DATA TENANT',
                'menu'      => 'tenant',
            );
            $datas = TblToko::where('kodetoko', $id)->first();           

            return view('tenant.edit', compact('data','datas'));
        } catch (QueryException $ex) {
            return abort(500);
        }
    }    
    
    public function tenantUpdate(Request $request){
        if($request->isMethod("POST"))
        {
            try{

                $data             = TblToko::where('kodetoko', $request->id)->first();
                $data->kodetoko   = $request->kode;
                $data->nama       = $request->nama;
                $data->pemilik    = $request->pemilik;
                $data->alamat     = $request->alamat;
                $data->telp       = $request->telepon;
                $data->email      = $request->email;
                $data->kota       = $request->kota;
                $data->ket        = $request->keterangan;
                $data->kelompok   = $request->kelompok;
                $data->longitude  = $request->longitude;
                $data->latitude   = $request->latitude;

                if ($request->deskripsi == '') {
                    $data->deskripsi = '-';
                }else{
                    $data->deskripsi  = $request->deskripsi;
                }

                if ($request->password == '') {                    
                    $data->pass       = $data->pass;
                }elseif($request->password !== ''){
                    try{
                        if ($request->password== $request->password1) {
                            $data->pass        = md5($request->password);
                        }else{
                            return redirect()->back()->with("fail", "Kolom Password Baru dan Konfirmasi Password tidak sama, Silahkan coba lagi!");
                        }               
                        
                    }catch (QueryException $ex){
                        return back()->with("fail", "Gagal ubah password " . $ex->getMessage())->withInput();
                    }
                }

                if ($request->rating == '') {
                    $data->rating = '0';
                }else{
                    $data->rating     = $request->rating;
                }

                if ($request->catatan == '') {
                    $data->catatan    = '-';
                }else{
                    $data->catatan    = $request->catatan;
                }

                if ($request->file('foto') == '') {
                    $data->gambar = $request->fotolama;
                }else{
                    if ($request->hasfile('foto')) {
                        $size = $request->file('foto')->getSize();
                        if ($size<=2097152) {
                            $time       = Carbon::now();
                            $file = $request->file('foto');
                            $extension = $file->getClientOriginalExtension();
                            $filename = $request->fotolama;
                            $file->move('uploads/tenants', $filename);
                            $data->gambar = $filename;                       
                        }else{
                            return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                        }                    
                    }
                                      
                    $data->save();
                    return redirect()->route("tenant")->with("success", "Berhasil ubah data tenant <strong>" . $data->nama . "</strong>");
                }

                $data->save();
                return redirect()->route("tenant")->with("success", "Berhasil ubah data tenant <strong>" . $data->nama . "</strong>");
                
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal ubah data tenant " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function tenantDelete(Request $request){
        try{
            $data = TblToko::where('kodetoko', $request->id)->first();
            $menu = TblMenu::where('kodetoko', $request->id)->get();

            if ($menu->count() > 0) {
                if($data && $menu){
                    if($menu){
                        foreach($menu as $item){
                            if($item->gambar != null || $item->gambar != '')
                            {                                
                                File::delete(realpath(public_path('uploads/menu')) . '/' . $item->gambar);
                            }

                            if ($item->where('kodetoko', $request->id)->delete()) {
                                if($data){
                                    if($data->gambar != null || $data->gambar != ''){
                                        File::delete(realpath(public_path('uploads/tenants')) . '/' . $data->gambar);
                                    }

                                    if($data->delete()){
                                        return redirect()->route("tenant")->with("success", "Berhasil hapus data tenant <strong>" . $data->nama_tenant . "</strong> beserta menunya");
                                    }else{
                                        return redirect()->route("tenant")->with("fail", "Gagal hapus tenant <strong>" . $data->nama_tenant . "</strong>, coba lagi!");
                                    }
                                }else{
                                    return back()->with("fail", "Tidak ditemukan data tenant");
                                }
                            }else{
                                return redirect()->route("tenant")->with("fail", "Gagal hapus tenant <strong>" . $data->nama_tenant . "</strong>, coba lagi!");
                            }
                        }
                    }
                }else{
                    return back()->with("fail", "Tidak ditemukan data tenant");
                }
            }else{
                if($data){
                    if($data->gambar != null || $data->gambar != ''){
                        File::delete(realpath(public_path('uploads/tenants')) . '/' . $data->gambar);
                    }

                    if($data->delete()){
                        return redirect()->route("tenant")->with("success", "Berhasil hapus data tenant <strong>" . $data->nama_tenant . "</strong>");
                    }else{
                        return redirect()->route("tenant")->with("fail", "Gagal hapus tenant <strong>" . $data->nama_tenant . "</strong>, coba lagi!");
                    }
                }else{
                    return back()->with("fail", "Tidak ditemukan data tenant");
                }
            }
            
        }catch (QueryException $ex) {
            return back()->with("fail", "Gagal hapus data tenant" . $ex->getMessage());
        }
    }

    public function Buka(){
        try{
            $data        = TblToko::where('ket', 'Tutup')->update(['ket' => 'Buka']);
            if ($data) {
                return redirect()->route("tenant")->with("success", "Berhasil Membuka semua Merchant");
            }else{
                return redirect()->route("tenant")->with("fail", "Gagal Membuka semua Merchant");
            }
            
        }catch (QueryException $ex){
            return back()->with("fail", "Gagal Membuka semua Merchant " . $ex->getMessage())->withInput();
        }
    }

    public function Tutup(){
        try{
            $data        = TblToko::where('ket', 'Buka')->update(['ket' => 'Tutup']);
            if ($data) {
                return redirect()->route("tenant")->with("success", "Berhasil Menutup semua Merchant");
            }else{
                return redirect()->route("tenant")->with("fail", "Gagal Menutup semua Merchant");
            }
        }catch (QueryException $ex){
            return back()->with("fail", "Gagal Menutup semua Merchant " . $ex->getMessage())->withInput();
        }
    }


}
