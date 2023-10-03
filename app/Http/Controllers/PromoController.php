<?php

namespace App\Http\Controllers;

use App\Models\TblPromo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Helpers\MyHelper;

class PromoController extends Controller
{
    public function index(){
        $data = (object)array(
            'title'     => 'PROMO',
            'menu'      => 'promo',       
        );
        return view('promo.promo', compact('data'));
    }

    public function promoCreate(){
        $data = (object)array(
            'title'     => 'TAMBAH PROMO',
            'menu'      => 'promo',
            'tanggal'   => now(),
        );

        return view('promo.add', compact('data'));
    }

    public function promoInsert(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblPromo::where('kodepromo', $request->kode)->first();
                if($dataExists)
                {
                    return back()->with("fail", "kode promo <strong>" . $request->kode . "</strong> sudah terdaftar")->withInput();
                }

                $data               = new TblPromo();
                $data->kodepromo    = $request->kode;
                $data->keterangan   = $request->keterangan;
                $data->periode_awal = $request->awal;
                $data->periode_akhir= $request->akhir;
                $data->nilai_persen = $request->nilaipersen;
                $data->nilai_rupiah = $request->nilairupiah;
                $data->status       = $request->status;

                if ($request->hasfile('foto')) {
                    $size = $request->file('foto')->getSize();
                    if ($size<=2097152) {
                        $time       = Carbon::now();
                        $file = $request->file('foto');
                        $extension = $file->getClientOriginalExtension();
                        $filename = "IMG-".Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . '.' . $extension;
                        $file->move('uploads/promo', $filename);
                        $data->gambar = $filename;                       
                    }else{
                        return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                    }                    
                }else{
                    return back()->with("fail", "Gagal menyimpan gambar")->withInput();
                }
                $data->save();
                return redirect()->route("promo")->with("success", "Berhasil tambah data promo <strong>" . $data->kodepromo . "</strong>");

            }catch (QueryException $ex){
                return back()->with("fail", "Gagal tambah data promo " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function promoData(Request $request){
        $columns = array(
            1 => 'kodepromo',
            2 => 'keterangan',
            3 => 'periode_awal',
            4 => 'periode_akhir',
            5 => 'nilai_persen',
            6 => 'nilai_rupiah',
            7 => 'status',
        );

        $totalData = TblPromo::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = TblPromo::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas =  TblPromo::where(function ($query) use ($search){
                        $query->orWhere('kodepromo','LIKE',"%{$search}%")
                            ->orWhere('keterangan','LIKE',"%{$search}%")
                            ->orWhere('periode_awal','LIKE',"%{$search}%")
                            ->orWhere('periode_akhir','LIKE',"%{$search}%")
                            ->orWhere('nilai_persen','LIKE',"%{$search}%")
                            ->orWhere('nilai_rupiah','LIKE',"%{$search}%")
                            ->orWhere('status','LIKE',"%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

            $totalFiltered = TblPromo::where(function ($query) use ($search){
                            $query->orWhere('kodepromo','LIKE',"%{$search}%")
                                ->orWhere('keterangan','LIKE',"%{$search}%")
                                ->orWhere('periode_awal','LIKE',"%{$search}%")
                                ->orWhere('periode_akhir','LIKE',"%{$search}%")
                                ->orWhere('nilai_persen','LIKE',"%{$search}%")
                                ->orWhere('nilai_rupiah','LIKE',"%{$search}%")
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
                $nestedData['id']          = [$item->id . ',' . $item->kodepromo . ',' . route("promo.edit", ['id' => $item->id]) . ',' . route("promo.delete", ['id' => $item->id])];
                $nestedData['kode']        = $item->kodepromo;
                $nestedData['persen']      = '<div class="text-right">'.number_format($item->nilai_persen,2,',','.').'%</div>';
                $nestedData['rupiah']      = '<div class="text-right">Rp. '.number_format($item->nilai_rupiah,0,',','.').'</div>';
                $nestedData['awal']        = MyHelper::date_id($item->periode_awal);
                $nestedData['akhir']       = MyHelper::date_id($item->periode_akhir);
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
                                                                <img width="100%" src="'.asset("uploads/promo"). "/" .$item->gambar.'">
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

    public function promoEdit(Request $request){
        try{
            $id = $request->id;
            $data = (object)array(
                'title'     => 'EDIT DATA PROMO',
                'menu'      => 'promo',
            );
            $datas = TblPromo::where('id', $id)->first();           

            return view('promo.edit', compact('data','datas'));
        } catch (QueryException $ex) {
            return abort(500);
        }
    }    
    
    public function promoUpdate(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblPromo::where('id', '<>',$request->id)->where('kodepromo', $request->kode)->first();
                if($dataExists)
                {
                    return back()->with("fail", "Promo <strong>" . $request->kode . "</strong> sudah ada")->withInput();
                }

                $data               = TblPromo::where('id', $request->id)->first();
                $data->kodepromo    = $request->kode;
                $data->keterangan   = $request->keterangan;
                $data->periode_awal = $request->awal;
                $data->periode_akhir= $request->akhir;
                $data->nilai_persen = $request->nilaipersen;
                $data->nilai_rupiah = $request->nilairupiah;
                $data->status       = $request->status;

                if ($request->hasfile('foto')) {
                    $size = $request->file('foto')->getSize();
                    if ($size<=2097152) {
                        $time       = Carbon::now();
                        $file = $request->file('foto');
                        $extension = $file->getClientOriginalExtension();
                        $filename = $request->fotolama;
                        $file->move('uploads/promo', $filename);
                        $data->gambar = $filename;                       
                    }else{
                        return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                    }                    
                }

                $data->save();
                return redirect()->route("promo")->with("success", "Berhasil ubah data promo <strong>" . $data->kodepromo . "</strong>");
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal ubah data promo " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function promoDelete(Request $request){
        try{
            $data = TblPromo::where('id', $request->id)->first();
            if($data)
            {
                if($data->gambar != null || $data->gambar != '')
                {
                    File::delete(realpath(public_path('uploads/promo')) . '/' . $data->gambar);
                }

                if($data->delete())
                    {
                        return redirect()->route("promo")->with("success", "Berhasil hapus data promo <strong>" . $data->kodepromo . "</strong>");
                    }
                    else
                    {
                        return redirect()->route("promo")->with("fail", "Gagal hapus promo <strong>" . $data->kodepromo . "</strong>, coba lagi!");
                    }
            }
            else
            {
                return back()->with("fail", "Tidak ditemukan data promo");
            }
        }catch (QueryException $ex) {
            return back()->with("fail", "Gagal hapus data promo" . $ex->getMessage());
        }
    }
}
