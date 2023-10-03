<?php

namespace App\Http\Controllers;

use App\Models\TblKontenBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class KontenController extends Controller
{
    public function index(){
        $data = (object)array(
            'title'     => 'KONTEN BANNER',
            'menu'      => 'konten-banner',       
        );
        return view('konten.konten', compact('data'));
    }

    public function kontenCreate(){
        $data = (object)array(
            'title'     => 'TAMBAH KONTEN BANNER',
            'menu'      => 'konten-banner',
        );

        return view('konten.add', compact('data'));
    }

    public function kontenInsert(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblKontenBanner::where('uraian', $request->uraian)->first();
                if($dataExists)
                {
                    return back()->with("fail", "uraian Konten Promo <strong>" . $request->uraian . "</strong> sudah terdaftar")->withInput();
                }

                $data               = new TblKontenBanner();
                $data->uraian       = $request->uraian;
                $data->tautan       = $request->tautan;
                $data->status       = $request->status;

                if ($request->hasfile('foto')) {
                    $size = $request->file('foto')->getSize();
                    if ($size<=2097152) {
                        $time       = Carbon::now();
                        $file = $request->file('foto');
                        $extension = $file->getClientOriginalExtension();
                        $filename = "IMG-".Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . '.' . $extension;
                        $file->move('uploads/konten', $filename);
                        $data->gambar = $filename;                       
                    }else{
                        return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                    }                    
                }else{
                    return back()->with("fail", "Gagal menyimpan gambar")->withInput();
                }
                $data->save();                

                return redirect()->route("konten")->with("success", "Berhasil tambah data konten banner <strong>" . $data->uraian . "</strong>");
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal tambah data konten banner " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function kontenData(Request $request){
        $columns = array(
            1 => 'uraian',
            2 => 'tautan',
            5 => 'status',
        );

        $totalData = TblKontenBanner::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblkontenbanner')
                    ->selectRaw('*')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblkontenbanner')
                    ->selectRaw('*')
                    ->where(function ($query) use ($search){
                        $query->orWhere('uraian','LIKE',"%{$search}%")
                            ->orWhere('tautan','LIKE',"%{$search}%")
                            ->orWhere('status','LIKE',"%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

            $totalFiltered = 
            $datas = DB::table('tblkontenbanner')
                        ->selectRaw('*')
                        ->where(function ($query) use ($search){
                            $query->orWhere('uraian','LIKE',"%{$search}%")
                                ->orWhere('tautan','LIKE',"%{$search}%")
                                ->orWhere('status','LIKE',"%{$search}%");
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
                $nestedData['id']          = [$item->id . ',' . $item->uraian . ',' . route("konten.edit", ['id' => $item->id]) . ',' . route("konten.delete", ['id' => $item->id])];
                $nestedData['uraian']      = $item->uraian;
                $nestedData['tautan']      = $item->tautan;
                $nestedData['status']      = $item->status == '1' ? '<center><small><label class="bg-info text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Aktif</label></small></center>': '<center><small><label class="bg-danger text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Tidak Aktif</label></small></center>';
                $nestedData['gambar']      = $item->gambar == null? '':'<div class="col-md-4 col-sm-1 grid-element">
                                                    <div class="tile-basic tile-basic-hover-shadow tile-basic-bordered">
                                                        <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModalCenter'.$item->id.'">
                                                            <span class="fad fa-image"></span> Lihat
                                                        </a>                                                      
                                                    </div>
                                               </div>
                                               <div class="modal fade" id="exampleModalCenter'.$item->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Preview</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img width="100%" src="'.asset("uploads/konten"). "/" .$item->gambar.'">
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

    public function kontenEdit(Request $request){
        try{
            $id = $request->id;
            $data = (object)array(
                'title'     => 'EDIT DATA KONTEN BANNER',
                'menu'      => 'konten-banner',
            );
            $datas = TblKontenBanner::where('id', $id)->first();           

            return view('konten.edit', compact('data','datas'));
        } catch (QueryException $ex) {
            return abort(500);
        }
    }    
    
    public function kontenUpdate(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblKontenBanner::where('id', '<>',$request->id)->where('uraian', $request->uraian)->first();
                if($dataExists)
                {
                    return back()->with("fail", "konten banner <strong>" . $request->uraian . "</strong> sudah ada")->withInput();
                }

                $data               = TblKontenBanner::where('id', $request->id)->first();
                $data->uraian       = $request->uraian;
                $data->tautan       = $request->tautan;
                $data->status       = $request->status;

                if ($request->hasfile('foto')) {
                    $size = $request->file('foto')->getSize();
                    if ($size<=2097152) {
                        $time       = Carbon::now();
                        $file       = $request->file('foto');
                        $extension  = $file->getClientOriginalExtension();
                        $filename   = $request->fotolama;
                        $file->move('uploads/konten', $filename);
                        $data->gambar = $filename;                       
                    }else{
                        return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                    }                    
                }

                $data->save();
                return redirect()->route("konten")->with("success", "Berhasil ubah data konten banner <strong>" . $data->uraian . "</strong>");
                
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal ubah data konten banner " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function kontenDelete(Request $request){
        try{
            $data = TblKontenBanner::where('id', $request->id)->first();
            if($data)
            {
                if($data->gambar != null || $data->gambar != '')
                {
                    File::delete(realpath(public_path('uploads/konten')) . '/' . $data->gambar);
                }

                if($data->delete())
                    {
                        return redirect()->route("konten")->with("success", "Berhasil hapus data konten banner <strong>" . $data->uraian . "</strong>");
                    }
                    else
                    {
                        return redirect()->route("konten")->with("fail", "Gagal hapus konten banner <strong>" . $data->uraian . "</strong>, coba lagi!");
                    }
            }
            else
            {
                return back()->with("fail", "Tidak ditemukan data konten banner");
            }
        }catch (QueryException $ex) {
            return back()->with("fail", "Gagal hapus data konten banner" . $ex->getMessage());
        }
    }
}
