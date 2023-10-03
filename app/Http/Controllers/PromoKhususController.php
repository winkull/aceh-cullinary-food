<?php

namespace App\Http\Controllers;

use App\Models\TblPromoKhusus;
use App\Models\TblPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Helpers\MyHelper;

class PromoKhususController extends Controller
{
    public function index(){
        $data = (object)array(
            'title'     => 'PROMO KHUSUS',
            'menu'      => 'promo-khusus',       
        );
        return view('promokhusus.promokhusus', compact('data'));
    }

    public function promoCreate(){
        $data = (object)array(
            'title'     => 'TAMBAH PROMO KHUSUS',
            'menu'      => 'promo-khusus',
            'pengguna'  => TblPengguna::all(),
            'tanggal'   => now(),
        );

        return view('promokhusus.add', compact('data'));
    }

    public function promoInsert(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblPromoKhusus::where('kodepromo', $request->kode)->first();
                if($dataExists)
                {
                    return back()->with("fail", "kode promo khusus <strong>" . $request->kode . "</strong> sudah terdaftar")->withInput();
                }

                $data               = new TblPromoKhusus();
                $data->kodepromo    = $request->kode;
                $data->keterangan   = $request->keterangan;
                $data->periode_awal = $request->awal;
                $data->periode_akhir= $request->akhir;
                $data->idpengguna   = $request->pengguna;                
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
                        $file->move('uploads/promokhusus', $filename);
                        $data->gambar = $filename;                       
                    }else{
                        return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                    }                    
                }else{
                    return back()->with("fail", "Gagal menyimpan gambar")->withInput();
                }
                $data->save();                

                return redirect()->route("promokhusus")->with("success", "Berhasil tambah data promo khusus <strong>" . $data->kodepromo . "</strong>");
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal tambah data promo khusus " . $ex->getMessage())->withInput();
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
            5 => 'idpengguna',
            7 => 'nilai_persen',
            8 => 'nilai_rupiah',
            9 => 'status',
        );

        $totalData = DB::table('tblpromokhusus as a')
                     ->join('tblpengguna as b', 'a.idpengguna', 'b.idpengguna')
                     ->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblpromokhusus as a')
                    ->join('tblpengguna as b', 'a.idpengguna', 'b.idpengguna')
                    ->selectRaw('a.*, b.namapengguna')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblpromokhusus as a')
                    ->join('tblpengguna as b', 'a.idpengguna', 'b.idpengguna')
                    ->selectRaw('a.*, b.namapengguna')
                    ->where(function ($query) use ($search){
                        $query->orWhere('a.kodepromo','LIKE',"%{$search}%")
                            ->orWhere('a.keterangan','LIKE',"%{$search}%")
                            ->orWhere('a.periode_awal','LIKE',"%{$search}%")
                            ->orWhere('a.periode_akhir','LIKE',"%{$search}%")
                            ->orWhere('b.namapengguna','LIKE',"%{$search}%")
                            ->orWhere('a.nilai_persen','LIKE',"%{$search}%")
                            ->orWhere('a.nilai_rupiah','LIKE',"%{$search}%")
                            ->orWhere('a.status','LIKE',"%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

            $totalFiltered = DB::table('tblpromokhusus as a')
                    ->join('tblpengguna as b', 'a.idpengguna', 'b.idpengguna')
                    ->where(function ($query) use ($search){
                        $query->orWhere('a.kodepromo','LIKE',"%{$search}%")
                            ->orWhere('a.keterangan','LIKE',"%{$search}%")
                            ->orWhere('a.periode_awal','LIKE',"%{$search}%")
                            ->orWhere('a.periode_akhir','LIKE',"%{$search}%")
                            ->orWhere('b.namapengguna','LIKE',"%{$search}%")
                            ->orWhere('a.nilai_persen','LIKE',"%{$search}%")
                            ->orWhere('a.nilai_rupiah','LIKE',"%{$search}%")
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
                $nestedData['id']          = [$item->id . ',' . $item->kodepromo . ',' . route("promokhusus.edit", ['id' => $item->id]) . ',' . route("promokhusus.delete", ['id' => $item->id])];
                $nestedData['kode']        = $item->kodepromo;
                $nestedData['persen']      = '<div class="text-right">'.number_format($item->nilai_persen,2,',','.').'%</div>';
                $nestedData['rupiah']      = '<div class="text-right">Rp. '.number_format($item->nilai_rupiah,0,',','.').'</div>';
                $nestedData['awal']        = MyHelper::date_id($item->periode_awal);
                $nestedData['akhir']       = MyHelper::date_id($item->periode_akhir);
                $nestedData['pengguna']  = $item->namapengguna;
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
                                                                <img width="100%" src="'.asset("uploads/promokhusus"). "/" .$item->gambar.'">
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
                'menu'      => 'promo-khusus',
                'pengguna'  => TblPengguna::all(),
            );
            $datas = TblPromoKhusus::where('id', $id)->first();           

            return view('promokhusus.edit', compact('data','datas'));
        } catch (QueryException $ex) {
            return abort(500);
        }
    }    
    
    public function promoUpdate(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblPromoKhusus::where('id', '<>',$request->id)->where('kodepromo', $request->kode)->first();
                if($dataExists)
                {
                    return back()->with("fail", "Promo Khusus <strong>" . $request->kode . "</strong> sudah ada")->withInput();
                }

                $data               = TblPromoKhusus::where('id', $request->id)->first();
                $data->kodepromo    = $request->kode;
                $data->keterangan   = $request->keterangan;
                $data->periode_awal = $request->awal;
                $data->periode_akhir= $request->akhir;
                $data->idpengguna   = $request->pengguna;
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
                        $file->move('uploads/promokhusus', $filename);
                        $data->gambar = $filename;                       
                    }else{
                        return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                    }                    
                }else{
                    $data->save();
                    return redirect()->route("promokhusus")->with("success", "Berhasil ubah data promo khusus <strong>" . $data->kodepromo . "</strong>");
                }
                return redirect()->route("promokhusus")->with("success", "Berhasil ubah data promo khusus <strong>" . $data->kodepromo . "</strong>");
                
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal ubah data promo khusus " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function promoDelete(Request $request){
        try{
            $data = TblPromoKhusus::where('id', $request->id)->first();
            if($data)
            {
                if($data->gambar != null || $data->gambar != '')
                {
                    File::delete(realpath(public_path('uploads/promokhusus')) . '/' . $data->gambar);
                }

                if($data->delete())
                    {
                        return redirect()->route("promokhusus")->with("success", "Berhasil hapus data promo khusus <strong>" . $data->kodepromo . "</strong>");
                    }
                    else
                    {
                        return redirect()->route("promokhusus")->with("fail", "Gagal hapus promo khusus <strong>" . $data->kodepromo . "</strong>, coba lagi!");
                    }
            }
            else
            {
                return back()->with("fail", "Tidak ditemukan data promo");
            }
        }catch (QueryException $ex) {
            return back()->with("fail", "Gagal hapus data promo khusus" . $ex->getMessage());
        }
    }
}
