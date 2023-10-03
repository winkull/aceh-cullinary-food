<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblMenu;
use App\Models\TblToko;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    public function index(Request $request){
        $toko   = TblToko::where('kodetoko', $request->id)->first();
        $data = (object)array(
            'title'     => 'MENU',
            'menu'      => 'tenant',
            'nama'      =>  $toko->nama,
            'kode'      =>  $toko->kodetoko,     
        );
        return view('menu.menu', compact('data'));
    }

    public function menuCreate(Request $request){
        $toko   = TblToko::where('kodetoko', $request->id)->first();
        $data = (object)array(
            'title'     => 'TAMBAH MENU',
            'menu'      => 'tenant',
            'kodetoko'  => $toko->kodetoko,
            'nama'      => $toko->nama,
        );

        return view('menu.add', compact('data'));
    }

    public function menuInsert(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblMenu::where('namamenu', $request->nama)->where('kodetoko', $request->id)->first();
                if($dataExists)
                {
                    return back()->with("fail", "menu <strong>" . $request->nama . "</strong> sudah terdaftar")->withInput();
                }

                $data             = new TblMenu();
                $data->namamenu   = $request->namamenu;
                $data->sisastock  = $request->stock;
                $data->harga      = $request->harga;
                $data->deskripsi  = $request->deskripsi;
                $data->jenis      = $request->jenis;
                $data->kodetoko   = $request->id;             
                $data->status     = $request->status;

                if ($request->diskon  == '') {
                    $data->diskon = 0;
                }else{
                    $data->diskon     = $request->diskon;
                }

                if ($request->persen  == '') {
                    $data->persen = 0;
                }else{
                    $data->persen     = $request->persen;
                }

                if ($request->hasfile('foto')) {
                    $size = $request->file('foto')->getSize();
                    if ($size<=2097152) {
                        $time       = Carbon::now();
                        $file = $request->file('foto');
                        $extension = $file->getClientOriginalExtension();
                        $filename = "IMG-".Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . '.' . $extension;
                        $file->move('uploads/menu', $filename);
                        $data->gambar = $filename;                       
                    }else{
                        return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                    }                    
                }else{
                    return back()->with("fail", "Gagal menyimpan gambar")->withInput();
                }
                $data->save();                

                return redirect()->route("menu",['id' =>$data->kodetoko ])->with("success", "Berhasil tambah menu <strong>" . $data->namamenu . "</strong>");
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal tambah data tenant " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function menuData(Request $request){
        $columns = array(
            1 => 'namamenu',
            2 => 'sisastock',
            3 => 'harga',
            4 => 'deskripsi',
            5 => 'jenis',
            6 => 'diskon',
            7 => 'persen',
            8 => 'gambar',
            9 => 'status',
        );

        $totalData = TblMenu::where('kodetoko', $request->id)->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmenu as a')
                    ->join('tbltoko as b', 'a.kodetoko', 'b.kodetoko')
                    ->selectRaw('a.*')
                    ->where('a.kodetoko', $request->id)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmenu as a')
                    ->join('tbltoko as b', 'a.kodetoko', 'b.kodetoko')
                    ->selectRaw('a.*')
                    ->where('a.kodetoko', $request->id)
                    ->where(function ($query) use ($search){
                        $query->orWhere('a.kodemenu','LIKE',"%{$search}%")
                            ->orWhere('a.namamenu','LIKE',"%{$search}%")
                            ->orWhere('a.sisastock','LIKE',"%{$search}%")
                            ->orWhere('a.harga','LIKE',"%{$search}%")
                            ->orWhere('a.deskripsi','LIKE',"%{$search}%")
                            ->orWhere('a.jenis','LIKE',"%{$search}%")
                            ->orWhere('a.diskon','LIKE',"%{$search}%")
                            ->orWhere('a.persen','LIKE',"%{$search}%")
                            ->orWhere('a.status','LIKE',"%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

            $totalFiltered = DB::table('tblmenu as a')
                            ->join('tbltoko as b', 'a.kodetoko', 'b.kodetoko')
                            ->selectRaw('a.*')
                            ->where('a.kodetoko', $request->id)
                            ->where(function ($query) use ($search){
                                $query->orWhere('a.kodemenu','LIKE',"%{$search}%")
                                    ->orWhere('a.namamenu','LIKE',"%{$search}%")
                                    ->orWhere('a.sisastock','LIKE',"%{$search}%")
                                    ->orWhere('a.harga','LIKE',"%{$search}%")
                                    ->orWhere('a.deskripsi','LIKE',"%{$search}%")
                                    ->orWhere('a.jenis','LIKE',"%{$search}%")
                                    ->orWhere('a.diskon','LIKE',"%{$search}%")
                                    ->orWhere('a.persen','LIKE',"%{$search}%")
                                    ->orWhere('a.status','LIKE',"%{$search}%");
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
                $nestedData['id']          = [$item->kodemenu . ',' . $item->namamenu . ',' . route("menu.edit", ['id' => $item->kodemenu]) . ',' . route("menu.delete", ['id' => $item->kodemenu])];
                $nestedData['nama']        = $item->namamenu;
                $nestedData['stok']        = $item->sisastock;
                $nestedData['harga']       = '<div class="text-right">Rp. '.number_format($item->harga,0,',','.').'</div>';
                $nestedData['deskripsi']   = $item->deskripsi;
                $nestedData['jenis']       = $item->jenis == 'Makanan' ? '<center><small><label class="bg-success text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">'.$item->jenis.'</label></small></center>': '<center><small><label class="bg-primary text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">'.$item->jenis.'</label></small></center>';
                $nestedData['diskon']      = '<div class="text-right">Rp. '.number_format($item->diskon,0,',','.').'</div>';
                $nestedData['persen']      = '<div class="text-right">'.number_format($item->persen,2,',','.').'%</div';
                $nestedData['status']      = $item->status == 1 ? '<center><small><label class="bg-info text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Aktif</label></small></center>': '<center><small><label class="bg-danger text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Tidak Aktif</label></small></center>';
                $nestedData['gambar']      = $item->gambar == null? '':'<div class="col-md-4 col-sm-1 grid-element">
                                                    <div class="tile-basic tile-basic-hover-shadow tile-basic-bordered">
                                                        <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModalCenter'.$item->kodemenu.'">
                                                            <span class="fad fa-image"></span> Lihat
                                                        </a>                                                      
                                                    </div>
                                               </div>
                                               <div class="modal fade" id="exampleModalCenter'.$item->kodemenu.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Preview</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img width="100%" src="'.asset("uploads/menu"). "/" .$item->gambar.'">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';
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

    public function menuEdit(Request $request){
        try{
            $id = $request->id;
            $data = (object)array(
                'title'     => 'EDIT DATA MENU',
                'menu'      => 'tenant',
            );
            $datas = TblMenu::where('kodemenu', $id)->first();           

            return view('menu.edit', compact('data','datas'));
        } catch (QueryException $ex) {
            return abort(500);
        }
    }    
    
    public function menuUpdate(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblMenu::where('kodemenu', '<>',$request->id)->where('namamenu', $request->nama)->first();
                if($dataExists)
                {
                    return back()->with("fail", "menu <strong>" . $request->nama . "</strong> sudah ada")->withInput();
                }

                $data             = TblMenu::where('kodemenu', $request->id)->first();
                $data->namamenu   = $request->namamenu;
                $data->sisastock  = $request->stock;
                $data->harga      = $request->harga;
                $data->deskripsi  = $request->deskripsi;
                $data->jenis      = $request->jenis;
                $data->kodetoko   = $request->kode;
                $data->status     = $request->status;


                if ($request->diskon  == '') {
                    $data->diskon = 0;
                }else{
                    $data->diskon     = $request->diskon;
                }

                if ($request->persen  == '') {
                    $data->persen = 0;
                }else{
                    $data->persen     = $request->persen;
                }

                if ($request->hasfile('foto')) {
                    $size = $request->file('foto')->getSize();
                    if ($size<=2097152) {
                        $time       = Carbon::now();
                        $file = $request->file('foto');
                        $extension = $file->getClientOriginalExtension();
                        $filename = "IMG-".Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . '.' . $extension;
                        $file->move('uploads/menu', $filename);
                        $data->gambar = $filename;                       
                    }else{
                        return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                    }                    
                }
                
                $data->save();
                return redirect()->route("menu",['id' => $request->kode])->with("success", "Berhasil ubah data menu <strong>" . $data->namamenu . "</strong>");
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal ubah data tenant " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function menuDelete(Request $request){
        try{
            $data = TblMenu::where('kodemenu', $request->id)->first();
            if($data)
            {
                if($data->gambar != null || $data->gambar != '')
                {
                    File::delete(realpath(public_path('uploads/menu')) . '/' . $data->gambar);
                }

                if($data->delete())
                    {
                        return redirect()->back()->with("success", "Berhasil hapus menu <strong>" . $data->namamenu . "</strong>");
                    }
                    else
                    {
                        return redirect()->back()->with("fail", "Gagal hapus menu <strong>" . $data->namamenu . "</strong>, coba lagi!");
                    }
            }
            else
            {
                return back()->with("fail", "Tidak ditemukan data menu");
            }
        }catch (QueryException $ex) {
            return back()->with("fail", "Gagal hapus data menu" . $ex->getMessage());
        }
    }
}
