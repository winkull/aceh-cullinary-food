<?php

namespace App\Http\Controllers;

use App\Models\TblPameran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PameranController extends Controller
{
     public function index(){
        $data = (object)array(
            'title'     => 'PAMERAN',
            'menu'      => 'pameran',       
        );
        return view('pameran.pameran', compact('data'));
    }

    public function Create(){
        $data = (object)array(
            'title'     => 'TAMBAH PAMERAN',
            'menu'      => 'pameran',
        );

        return view('pameran.add', compact('data'));
    }

    public function Insert(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblPameran::where('judul', $request->judul)->first();
                if($dataExists)
                {
                    return back()->with("fail", "judul pameran <strong>" . $request->judul . "</strong> sudah terdaftar")->withInput();
                }

                $data             = new TblPameran();
                $data->judul      = $request->judul;
                $data->linkurl    = $request->linkurl;
                $data->status1    = $request->status;

                if ($request->deskripsi == '') {
                    $data->deskripsi = '-';
                }else{
                    $data->deskripsi  = $request->deskripsi;
                }

                if ($request->hasfile('foto')) {
                        $size = $request->file('foto')->getSize();
                        if ($size<=2097152) {
                            $time       = Carbon::now();
                            $file = $request->file('foto');
                            $extension = $file->getClientOriginalExtension();
                            $filename = "IMG-".Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . '.' . $extension;
                            $file->move('uploads/pameran', $filename);
                                $data->gambar = $filename;                       
                        }else{
                            return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                        }                    
                    }else{
                        return back()->with("fail", "Gagal menyimpan gambar")->withInput();
                }
                
                $data->save();                

                return redirect()->route("pameran")->with("success", "Berhasil tambah data pemeran <strong>" . $data->judul . "</strong>");
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal tambah data pemeran " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function Data(Request $request){
        $columns = array(
            1 => 'judul',
            2 => 'deskripsi',
            3 => 'linkurl',
            4 => 'status1',
            5 => 'gambar',
        );

        $totalData = TblPameran::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = TblPameran::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = TblPameran::where(function ($query) use ($search){
                        $query->orWhere('judul','LIKE',"%{$search}%")
                            ->orWhere('deskripsi','LIKE',"%{$search}%")
                            ->orWhere('linkurl','LIKE',"%{$search}%")
                            ->orWhere('status1','LIKE',"%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

            $totalFiltered = TblPameran::where(function ($query) use ($search){
                                $query->orWhere('judul','LIKE',"%{$search}%")
                            ->orWhere('deskripsi','LIKE',"%{$search}%")
                            ->orWhere('linkurl','LIKE',"%{$search}%")
                            ->orWhere('status1','LIKE',"%{$search}%");
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
                $nestedData['id']          = [$item->id . ',' . $item->judul . ',' . route("pameran.edit", ['id' => $item->id]) . ',' . route("pameran.delete", ['id' => $item->id])];
                $nestedData['judul']       = $item->judul;
                $nestedData['linkurl']     = $item->linkurl;
                $nestedData['status']      = $item->status1 == 1 ? '<center><small><label class="bg-info text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Aktif</label></small></center>': '<center><small><label class="bg-danger text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Tidak Aktif</label></small></center>';
                $nestedData['gambar']      = $item->gambar == 'NULL' ? '' : '<div class="col-md-4 col-sm-1 grid-element">
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
                                                                <img width="100%" src="'.asset("uploads/pameran"). "/" .$item->gambar.'">
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

    public function Edit(Request $request){
        try{
            $id = $request->id;
            $data = (object)array(
                'title'     => 'EDIT PAMERAN',
                'menu'      => 'pameran',
            );
            $datas = TblPameran::where('id', $id)->first();           

            return view('pameran.edit', compact('data','datas'));
        } catch (QueryException $ex) {
            return abort(500);
        }
    }    
    
    public function Update(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblPameran::where('id', '<>',$request->id)->where('judul', $request->judul)->first();
                if($dataExists)
                {
                    return back()->with("fail", "pameran <strong>" . $request->judul . "</strong> sudah ada")->withInput();
                }

                $data             = TblPameran::where('id', $request->id)->first();
                $data->judul      = $request->judul;
                $data->linkurl    = $request->linkurl;
                $data->status1    = $request->status;

                if ($request->deskripsi == '') {
                    $data->deskripsi = '-';
                }else{
                    $data->deskripsi  = $request->deskripsi;
                }

                if ($request->hasfile('foto')) {
                    $size = $request->file('foto')->getSize();
                    if ($size<=2097152) {
                        $time       = Carbon::now();
                        $file       = $request->file('foto');
                        $extension  = $file->getClientOriginalExtension();
                        $filename   = $request->fotolama;
                        $file->move('uploads/pameran', $filename);
                        $data->gambar = $filename;                       
                    }else{
                        return back()->with("fail", "Gambar melebihi batas upload")->withInput();
                    }                    
                }
                                      
                $data->save();
                return redirect()->route("pameran")->with("success", "Berhasil ubah data pameran <strong>" . $data->judul . "</strong>");
                
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal ubah data pameran " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function Delete(Request $request){
        try{
            $data = TblPameran::where('id', $request->id)->first();
            
            if($data){
                if($data->gambar != null || $data->gambar != ''){
                    File::delete(realpath(public_path('uploads/pameran')) . '/' . $data->gambar);
                }

                if($data->delete()){
                    return redirect()->route("pameran")->with("success", "Berhasil hapus data pameran <strong>" . $data->judul . "</strong>");
                }else{
                    return redirect()->route("pameran")->with("fail", "Gagal hapus pameran <strong>" . $data->judul . "</strong>, coba lagi!");
                }
            }else{
                return back()->with("fail", "Tidak ditemukan data pameran");
            }
            
        }catch (QueryException $ex) {
            return back()->with("fail", "Gagal hapus data pameran" . $ex->getMessage());
        }
    }
}
