<?php

namespace App\Http\Controllers;

use App\Models\TblToko;
use App\Models\TblMenu;
use App\Models\TblPengguna;
use App\Models\TblMasterPesanan;
use App\Models\TblDetailPesanan;
use App\Models\TblDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MainController extends Controller
{
    public function index(){
        $data = (object)array(
            'title'     => 'DASHBOARD',
            'block'     => 'data',
            'menu'      => 'dashboard',          
        );

        $toko       = TblToko::count();
        $menu       = TblMenu::count();
        $pengguna   = TblPengguna::count();
        $driver     = TblDriver::count();

        $jumlah_hari          = TblMasterPesanan::whereDate('tanggal', date('Y-m-d'))->where('status_pesanan', 'SELESAI')->count();
        $total_hari           = TblMasterPesanan::whereDate('tanggal', date('Y-m-d'))->where('status_pesanan', 'SELESAI')->sum('grandtotal');
        $total_transaksi_hari = 'Rp. '.number_format($total_hari,0,',','.');

        $jumlah_minggu          = TblMasterPesanan::whereBetween('tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                                  ->where('status_pesanan', 'SELESAI')
                                  ->count();
        $total_minggu           = TblMasterPesanan::whereBetween('tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                                  ->where('status_pesanan', 'SELESAI')
                                  ->sum('grandtotal');
        $total_transaksi_minggu = 'Rp. '.number_format($total_minggu,0,',','.');


        $jumlah_bulan           = TblMasterPesanan::whereMonth('tanggal', date('m'))->where('status_pesanan', 'SELESAI')->count();
        $total_bulan            = TblMasterPesanan::whereMonth('tanggal', date('m'))->where('status_pesanan', 'SELESAI')->sum('grandtotal');
        $total_transaksi_bulan  = 'Rp. '.number_format($total_bulan,0,',','.');

        return view('dashboard', compact('data','toko','menu','pengguna','driver','jumlah_hari','total_transaksi_hari','jumlah_minggu','total_transaksi_minggu','jumlah_bulan','total_transaksi_bulan'));
    }



    public function Jumlah(Request $request){

        $columns = array(
            1 => 'nama',
            2 => 'jumlah',
        );

        $totalData = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('sum(a.total_qty) as jumlah')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->groupBy('a.idtoko')
                    ->limit(10)
                    ->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.total_qty) as jumlah')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.total_qty) as jumlah')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->where(function ($query) use ($search){
                        $query->orWhere('b.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama']        = $item->nama;
                $nestedData['jumlah']      = $item->jumlah;
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

    public function Total(Request $request){

        $columns = array(
            1 => 'nama',
            2 => 'total',
        );

        $totalData = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('sum(a.grandtotal) as total')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->groupBy('a.idtoko')
                    ->limit(10)
                    ->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.grandtotal) as total')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.grandtotal) as total')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->where(function ($query) use ($search){
                        $query->orWhere('b.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama']        = $item->nama;
                $nestedData['total']       = '<div class="text-right">Rp. '.number_format($item->total,0,',','.').'</div>';
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

    public function JumlahMenu(Request $request){

        $columns = array(
            1 => 'namamenu',
            2 => 'nama',
            3 => 'jumlah',
        );

        $totalData = DB::table('tbldetail_pesanan as a')
                    ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                    ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                    ->selectRaw('sum(a.qty) as jumlah, c.nama, a.namamenu')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->groupBy('a.kodemenu')
                    ->limit(10)
                    ->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tbldetail_pesanan as a')
                    ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                    ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                    ->selectRaw('sum(a.qty) as jumlah, c.nama, a.namamenu')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->groupBy('a.kodemenu')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tbldetail_pesanan as a')
                    ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                    ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                    ->selectRaw('sum(a.qty) as jumlah, c.nama, a.namamenu')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->where(function ($query) use ($search){
                        $query->orWhere('a.namamenu','LIKE',"%{$search}%")
                        ->orWhere('c.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.kodemenu')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama-menu']   = $item->namamenu;
                $nestedData['nama']        = $item->nama;
                $nestedData['jumlah']      = $item->jumlah;
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

    public function JumlahDriver(Request $request){

        $columns = array(
            1 => 'nama',
            2 => 'namatoko',
            3 => 'jumlah',
        );

        $totalData = DB::table('tblmasterpesanan as a')
                    ->join('tbldriver as b', 'a.iddriver', 'b.id')
                    ->join('tbltoko as c', 'a.idtoko', 'c.kodetoko')                    
                    ->whereDate('a.tanggal', date('Y-m-d'))
                    ->groupBy('a.iddriver')
                    ->limit(10)
                    ->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbldriver as b', 'a.iddriver', 'b.id')
                    ->join('tbltoko as c', 'a.idtoko', 'c.kodetoko')
                    ->selectRaw('count(a.iddriver) as jumlah, b.nama, c.nama as namatoko')
                    ->whereDate('a.tanggal', date('Y-m-d'))
                    ->groupBy('a.iddriver')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbldriver as b', 'a.iddriver', 'b.id')
                    ->join('tbltoko as c', 'a.idtoko', 'c.kodetoko')
                    ->selectRaw('count(a.iddriver) as jumlah, b.nama, c.nama as namatoko')
                    ->whereDate('a.tanggal', date('Y-m-d'))
                    ->where(function ($query) use ($search){
                        $query->orWhere('b.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.iddriver')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama-driver'] = $item->nama;
                $nestedData['nama']        = $item->namatoko;
                $nestedData['jumlah']      = $item->jumlah;
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

    public function JumlahMinggu(Request $request){

        $columns = array(
            1 => 'nama',
            2 => 'jumlah',
        );

        $totalDatas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.total_qty) as jumlah')
                    ->whereBetween('a.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->groupBy('a.idtoko')
                    ->limit(10)
                    ->get();

        $totalData = $totalDatas->count();

        $totalFiltered = $totalDatas->count();

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.total_qty) as jumlah')
                    ->whereBetween('a.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.total_qty) as jumlah')
                    ->whereBetween('a.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->where(function ($query) use ($search){
                        $query->orWhere('b.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama']        = $item->nama;
                $nestedData['jumlah']      = $item->jumlah;
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

    public function TotalMinggu(Request $request){

        $columns = array(
            1 => 'nama',
            2 => 'total',
        );

        $totalDatas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.grandtotal) as total')
                    ->whereBetween('a.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->groupBy('a.idtoko')
                    ->limit(10)
                    ->get();

        $totalData = $totalDatas->count();

        $totalFiltered = $totalDatas->count();

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.grandtotal) as total')
                    ->whereBetween('a.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.grandtotal) as total')
                    ->whereBetween('a.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->where(function ($query) use ($search){
                        $query->orWhere('b.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama']        = $item->nama;
                $nestedData['total']       = '<div class="text-right">Rp. '.number_format($item->total,0,',','.').'</div>';
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

    public function JumlahMenuMinggu(Request $request){

        $columns = array(
            1 => 'namamenu',
            2 => 'nama',
            3 => 'jumlah',
        );

        $totalData = DB::table('tbldetail_pesanan as a')
                    ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                    ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                    ->selectRaw('sum(a.qty) as jumlah, c.nama, a.namamenu')
                    ->whereBetween('b.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->groupBy('a.kodemenu')
                    ->limit(10)
                    ->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tbldetail_pesanan as a')
                    ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                    ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                    ->selectRaw('sum(a.qty) as jumlah, c.nama, a.namamenu')
                    ->whereBetween('b.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->groupBy('a.kodemenu')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tbldetail_pesanan as a')
                    ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                    ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                    ->selectRaw('sum(a.qty) as jumlah, c.nama, a.namamenu')
                    ->whereBetween('b.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->where(function ($query) use ($search){
                        $query->orWhere('a.namamenu','LIKE',"%{$search}%")
                        ->orWhere('c.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.kodemenu')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama-menu']   = $item->namamenu;
                $nestedData['nama']        = $item->nama;
                $nestedData['jumlah']      = $item->jumlah;
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

    public function JumlahDriverMinggu(Request $request){

        $columns = array(
            1 => 'nama',
            2 => 'namatoko',
            3 => 'jumlah',
        );

        $totalData = DB::table('tblmasterpesanan as a')
                    ->join('tbldriver as b', 'a.iddriver', 'b.id')
                    ->join('tbltoko as c', 'a.idtoko', 'c.kodetoko') 
                    ->whereBetween('a.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->groupBy('a.iddriver')
                    ->limit(10)
                    ->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbldriver as b', 'a.iddriver', 'b.id')
                    ->join('tbltoko as c', 'a.idtoko', 'c.kodetoko')
                    ->selectRaw('count(a.iddriver) as jumlah, b.nama, c.nama as namatoko')
                    ->whereBetween('a.tanggal', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
                    ->groupBy('a.iddriver')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbldriver as b', 'a.iddriver', 'b.id')
                    ->join('tbltoko as c', 'a.idtoko', 'c.kodetoko')
                    ->selectRaw('count(a.iddriver) as jumlah, b.nama, c.nama as namatoko')
                    ->whereDate('a.tanggal', date('Y-m-d'))
                    ->where(function ($query) use ($search){
                        $query->orWhere('b.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.iddriver')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama-driver'] = $item->nama;
                $nestedData['nama']        = $item->namatoko;
                $nestedData['jumlah']      = $item->jumlah;
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

    public function JumlahBulan(Request $request){

        $columns = array(
            1 => 'nama',
            2 => 'jumlah',
        );

        $totalDatas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.total_qty) as jumlah')
                    ->whereMonth('a.tanggal', Carbon::now()->month)
                    ->groupBy('a.idtoko')
                    ->limit(10)
                    ->get();

        $totalData = $totalDatas->count();

        $totalFiltered = $totalDatas->count();

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.total_qty) as jumlah')
                    ->whereMonth('a.tanggal', Carbon::now()->month)
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.total_qty) as jumlah')
                    ->whereMonth('a.tanggal', Carbon::now()->month)
                    ->where(function ($query) use ($search){
                        $query->orWhere('b.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama']        = $item->nama;
                $nestedData['jumlah']      = $item->jumlah;
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

    public function TotalBulan(Request $request){

        $columns = array(
            1 => 'nama',
            2 => 'total',
        );

        $totalDatas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.grandtotal) as total')
                    ->whereMonth('a.tanggal', Carbon::now()->month)
                    ->groupBy('a.idtoko')
                    ->limit(10)
                    ->get();

        $totalData = $totalDatas->count();

        $totalFiltered = $totalDatas->count();

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.grandtotal) as total')
                    ->whereMonth('a.tanggal', Carbon::now()->month)
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                    ->selectRaw('b.nama, sum(a.grandtotal) as total')
                    ->whereMonth('a.tanggal', Carbon::now()->month)
                    ->where(function ($query) use ($search){
                        $query->orWhere('b.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.idtoko')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama']        = $item->nama;
                $nestedData['total']       = '<div class="text-right">Rp. '.number_format($item->total,0,',','.').'</div>';
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

    public function JumlahMenuBulan(Request $request){

        $columns = array(
            1 => 'namamenu',
            2 => 'nama',
            3 => 'jumlah',
        );

        $totalData = DB::table('tbldetail_pesanan as a')
                    ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                    ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                    ->selectRaw('sum(a.qty) as jumlah, c.nama, a.namamenu')
                    ->whereMonth('b.tanggal', Carbon::now()->month)
                    ->groupBy('a.kodemenu')
                    ->limit(10)
                    ->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tbldetail_pesanan as a')
                    ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                    ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                    ->selectRaw('sum(a.qty) as jumlah, c.nama, a.namamenu')
                    ->whereMonth('b.tanggal', Carbon::now()->month)
                    ->groupBy('a.kodemenu')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tbldetail_pesanan as a')
                    ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                    ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                    ->selectRaw('sum(a.qty) as jumlah, c.nama, a.namamenu')
                    ->whereMonth('b.tanggal', Carbon::now()->month)
                    ->where(function ($query) use ($search){
                        $query->orWhere('a.namamenu','LIKE',"%{$search}%")
                        ->orWhere('c.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.kodemenu')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama-menu']   = $item->namamenu;
                $nestedData['nama']        = $item->nama;
                $nestedData['jumlah']      = $item->jumlah;
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

    public function JumlahDriverBulan(Request $request){

        $columns = array(
            1 => 'nama',
            2 => 'namatoko',
            3 => 'jumlah',
        );

        $totalData = DB::table('tblmasterpesanan as a')
                    ->join('tbldriver as b', 'a.iddriver', 'b.id')
                    ->join('tbltoko as c', 'a.idtoko', 'c.kodetoko')
                    ->selectRaw('count(a.iddriver) as jumlah, b.nama, c.nama as namatoko')
                    ->whereMonth('a.tanggal', Carbon::now()->month)
                    ->groupBy('a.iddriver')
                    ->limit(10)
                    ->count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbldriver as b', 'a.iddriver', 'b.id')
                    ->join('tbltoko as c', 'a.idtoko', 'c.kodetoko')
                    ->selectRaw('count(a.iddriver) as jumlah, b.nama, c.nama as namatoko')
                    ->whereMonth('a.tanggal', Carbon::now()->month)
                    ->groupBy('a.iddriver')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = DB::table('tblmasterpesanan as a')
                    ->join('tbldriver as b', 'a.iddriver', 'b.id')
                    ->join('tbltoko as c', 'a.idtoko', 'c.kodetoko')
                    ->selectRaw('count(a.iddriver) as jumlah, b.nama, c.nama as namatoko')
                    ->whereMonth('a.tanggal', Carbon::now()->month)
                    ->where(function ($query) use ($search){
                        $query->orWhere('b.nama','LIKE',"%{$search}%");
                    })
                    ->groupBy('a.iddriver')
                    ->orderBy($order,$dir)
                    ->limit(10)
                    ->get();
        }

        $data = array();
        if(!empty($datas))
        {
            $no = 1;
            foreach ($datas as $item)
            {
                $nestedData['no']          = $no++;
                $nestedData['nama-driver'] = $item->nama;
                $nestedData['nama']        = $item->namatoko;
                $nestedData['jumlah']      = $item->jumlah;
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

}
