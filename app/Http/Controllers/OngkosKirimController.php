<?php

namespace App\Http\Controllers;

use App\Models\TblOngkir;
use Illuminate\Http\Request;

class OngkosKirimController extends Controller
{
    public function index(){
        $data = (object)array(
            'title'     => 'ONGKIR',
            'menu'      => 'ongkir',
            'ongkir'    => TblOngkir::where('id', 1)->first(),     
        );
        return view('ongkoskirim.ongkosKirim', compact('data'));
    }

    public function data(Request $request){
        $columns = array(
            1 => 'id',
            2 => 'biaya_kirim',

        );

        $totalData = TblOngkir::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = TblOngkir::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = TblOngkir::where(function ($query) use ($search){
                        $query->orWhere('biaya_kirim','LIKE',"%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

            $totalFiltered = TblOngkir::where(function ($query) use ($search){
                        $query->orWhere('biaya_kirim','LIKE',"%{$search}%");
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
                $nestedData['id']          = [$item->id . ',' . $item->biaya_kirim];
                $nestedData['ongkir']      = 'Rp. '.number_format($item->biaya_kirim,0,',','.');
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

    public function update(Request $request){
        if ($request->isMethod('POST')){
            try{
                
                $data              = TblOngkir::where('id', $request->id)->first();
                $data->biaya_kirim = $request->ongkir;
                $data->save();

                return redirect()->back()->with("success", "Berhasil ubah ongkir menjadi <strong>" . $data->biaya_kirim . "</strong>");                                   
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal ubah ongkos kirim " . $ex->getMessage())->withInput();
            } 
        }else
        {
            return abort(405);
        }
    }
}
