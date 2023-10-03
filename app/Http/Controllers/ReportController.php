<?php

namespace App\Http\Controllers;

use App\Models\TblToko;
use App\Models\TblMasterPesanan;
use App\Models\TblDetailPesanan;
use App\Models\TblMenu;
use App\Models\TblPengguna;
use Carbon\Carbon;
use App\Helpers\MyHelper;
use Illuminate\Http\Request;
use DB;
use PDF;

class ReportController extends Controller
{
    public function indexPesanan(){
        $data = (object)array(
            'title'     => 'LAPORAN',
            'menu'      => 'laporan',
            'tanggal'   => now(), 
            'tenant'    => TblToko::all(),
            ''
        );
        return view('laporan.data-pesanan.laporan', compact('data'));
    }

    public function tampilPesanan(Request $request){
        if ($request->isMethod('POST')) {
            try {
                $periode_awal = $request->periode_awal;
                $periode_akhir= $request->periode_akhir;
                $tenant       = $request->tenant;
                $status_kupon = $request->status_kupon;
                $status_pesan = $request->status_pesan;

                $master_pesanan = DB::table('tblmasterpesanan as a')
                                ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                                ->join('tblpengguna as c', 'a.idpemesan', 'c.idpengguna')
                                ->selectRaw('a.*, b.nama, c.namapengguna, b.kodetoko')
                                ->when($tenant!='all', function($query) use ($tenant){
                                    return $query->where('a.idtoko', $tenant);
                                })
                                ->when($status_kupon!='all', function($query) use ($status_kupon){
                                    if($status_kupon == 0){
                                        return $query->where('a.kupon', '');
                                    }elseif($status_kupon == 1){
                                        return $query->whereNotNull('a.kupon')->whereRaw('a.kupon <> ""');
                                    }
                                })
                                ->when($status_pesan!='all', function($query) use ($status_pesan){
                                    if($status_pesan == 0){
                                        return $query->where('a.status_pesanan', 'DRIVER_TOLAK')->orWhere('a.status_pesanan', 'MERCHANT_TOLAK');
                                    }elseif($status_pesan == 1){
                                        return $query->where('a.status_pesanan', 'SELESAI');
                                    }
                                })
                                ->whereBetween('a.tanggal', array($periode_awal,$periode_akhir))
                                ->get();

                if($tenant == 'all'){
                    $tenant_name = 'SEMUA TENANT/TOKO';
                }else{
                    $tenants = TblToko::where('kodetoko', $tenant)->first();
                    $tenant_name = $tenants->nama;
                }

                if ($status_kupon == 'all') {
                    $status_kupon_name = 'SEMUA STATUS KUPON';
                }elseif($status_kupon == '1') {
                    $status_kupon_name = 'PAKE KUPON';
                }elseif($status_kupon == '0') {
                    $status_kupon_name = 'TIDAK PAKE KUPON';
                }

                if ($status_pesan == 'all') {
                    $status_pesan_name = 'SEMUA STATUS PESANAN';
                }else{
                    $status_pesan_name = $status_pesan;
                }

                $report_top  = '<div class="row">
                                <div class="col-md-12">
                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Periode :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . MyHelper::date_id($periode_awal, 'j F Y', '') . ' s/d ' . MyHelper::date_id($periode_akhir, 'j F Y', '') . '</strong></div>

                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Nama Merchant :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . $tenant_name . '</strong></div>

                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Status Pesanan :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . $status_pesan_name . '</strong></div>
                                </div>
                            </div>'; 

                $report_data = '<thead>
                                    <tr>
                                        <th></th>
                                        <th>No</th>
                                        <th>No Faktur</th>
                                        <th>Tanggal</th>
                                        <th>Nama Merchant</th>
                                        <th>Nama Pemesan</th>
                                        <th>Jumlah</th>
                                        <th>Sub Total</th>
                                        <th>Kupon</th>
                                        <th>Nilai Kupon</th>
                                        <th>Ongkir</th>
                                        <th>Grand Total</th>
                                        <th>Status Pesanan</th>
                                        <th>Tanggal Status</th>
                                        <th>Status Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>';

                 if ($master_pesanan) {
                    $no = 1;
                    foreach($master_pesanan as $report){
                        $report_data .= '<tr>
                                            <td>
                                                <a href="javascript:void(0);" id="report-detail" data-id="'.$report->nomorpesanan.'" data-tenant="'.$report->kodetoko.'" data-startdate="'.$periode_awal.'" data-enddate="'.$periode_akhir.'" data-kupon="'.$status_kupon.'" data-pesan="'.$status_pesan.'" class="btn btn-xs btn-outline-info btn-icon popover-hover" data-content="Detail">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">' . $no++ . '</td>
                                            <td>' . $report->no_faktur . '</td>
                                            <td>' . MyHelper::date_id($report->tanggal) . '</td>
                                            <td>' . $report->nama  . '</td>
                                            <td>' . $report->namapengguna . '</td>
                                            <td class="text-right">' . $report->total_qty . '</td>
                                            <td class="text-right">Rp. ' . number_format($report->subtotal,0,',','.') . '</td>
                                            <td>' . $report->kupon . '</td>
                                            <td class="text-right">Rp. ' . number_format($report->nilai_kupon,0,',','.') . '</td>
                                            <td class="text-right">Rp. ' . number_format($report->ongkir,0,',','.') . '</td>
                                            <td class="text-right">Rp. ' . number_format($report->grandtotal,0,',','.') . '</td>
                                            <td>' . $report->status_pesanan . '</td>
                                            <td>' . MyHelper::date_id($report->tanggal_status) . '</td>
                                            <td>' . $report->status_bayar . '</td>
                                            
                                        </tr>';
                    }                        
                }

                $report_data .= '</body>';

                $response['status']     = 'success';
                $response['message']    = 'Data Laporan berhasil ditampilkan';
                $response['report']     = (object) array(
                    'top'               => $report_top,
                    'top_copy'          => (object) array(
                        'periode'           => "Periode :\n" . MyHelper::date_id($periode_awal, 'j F Y', '') . ' s/d ' . MyHelper::date_id($periode_akhir, 'j F Y', '') . "\n\n",
                        'tenant'            => "Nama Tenant/Toko :\n" . $tenant_name . "\n\n",
                        'kupon'             => "Status Kupon :\n" . $status_kupon_name . "\n\n",
                        'pesan'             => "Status Pesanan :\n" . $status_pesan_name . "\n\n",
                    ),
                    'data'              => $report_data,
                    'recap_btn'         => '<button id="recap-btn" type="submit" class="btn btn-primary"><span class="fad fa-file-chart-line"></span> Cetak Laporan</button>'
                );


            } catch (QueryException $ex) {
                $response['status']     = 'fail';
                $response['message']    = 'Database Error! ' . $ex->getMessage();
            }

            return response()->json($response);
        }
    }

    public function laporanPesananPdf(Request $request){

        try {

                $periode_awal = $request->periode_awal;
                $periode_akhir= $request->periode_akhir;
                $tenant       = $request->tenant;
                $status_kupon = $request->status_kupon;
                $status_pesan = $request->status_pesan;

                $no = 1;
                $reports = DB::table('tblmasterpesanan as a')
                                ->join('tbltoko as b', 'a.idtoko', 'b.kodetoko')
                                ->join('tblpengguna as c', 'a.idpemesan', 'c.idpengguna')
                                ->selectRaw('a.*, b.nama, c.namapengguna, b.kodetoko')
                                ->when($tenant!='all', function($query) use ($tenant){
                                    return $query->where('a.idtoko', $tenant);
                                })
                                ->when($status_kupon!='all', function($query) use ($status_kupon){
                                    if($status_kupon == 0){
                                        return $query->where('a.kupon', '');
                                    }elseif($status_kupon == 1){
                                        return $query->whereNotNull('a.kupon')->whereRaw('a.kupon <> ""');
                                    }
                                })
                                ->when($status_pesan!='all', function($query) use ($status_pesan){
                                    return $query->where('a.status_pesanan', $status_pesan);
                                })
                                ->whereBetween('a.tanggal', array($periode_awal,$periode_akhir))
                                ->get();

                if($tenant == 'all'){
                    $tenant_name = 'SEMUA TENANT/TOKO';
                }else{
                    $tenants = TblToko::where('kodetoko', $tenant)->first();
                    $tenant_name = $tenants->nama;
                }

                if ($status_pesan == 'all') {
                    $status_pesan_name = 'SEMUA STATUS PESANAN';
                }else{
                    $status_pesan_name = $status_pesan;
                }

            $pdf = PDF::loadView('laporan.data-pesanan.laporan-pdf', compact('no', 'reports', 'tenant_name', 'status_pesan_name', 'periode_awal', 'periode_akhir'))->setPaper('legal', 'landscape');
            return $pdf->stream('Laporan Pesanan.pdf');
        } catch (QueryException $ex) {
            return abort(405);
        }
    }

    public function tampilDetailPesanan(Request $request){
        if ($request->isMethod('POST')) {
            try {
                $id           = $request->id;
                $periode_awal = $request->periode_awal;
                $periode_akhir= $request->periode_akhir;
                $tenant       = $request->tenant;
                $status_kupon = $request->status_kupon;
                $status_pesan = $request->status_pesan;

                $detail_pesanan = DB::table('tbldetail_pesanan as a')
                                ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                                ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                                ->join('tblpengguna as d', 'b.idpemesan', 'd.idpengguna')
                                ->selectRaw('a.*, b.*, c.nama, d.namapengguna, c.kodetoko')
                                ->when($tenant!='all', function($query) use ($tenant){
                                    return $query->where('b.idtoko', $tenant);
                                })
                                ->when($status_kupon!='all', function($query) use ($status_kupon){
                                    if($status_kupon == 0){
                                        return $query->where('b.kupon', '');
                                    }elseif($status_kupon == 1){
                                        return $query->whereNotNull('b.kupon')->whereRaw('b.kupon <> ""');
                                    }
                                })
                                ->when($status_pesan!='all', function($query) use ($status_pesan){
                                    return $query->where('b.status_pesanan', $status_pesan);
                                })
                                ->whereBetween('b.tanggal', array($periode_awal,$periode_akhir))
                                ->where('a.nomorpesanan', $id)
                                ->get();

                if($tenant == 'all'){
                    $tenant_name = 'SEMUA TENANT/TOKO';
                }else{
                    $tenants = TblToko::where('kodetoko', $tenant)->first();
                    $tenant_name = $tenants->nama;
                }

                if ($status_kupon == 'all') {
                    $status_kupon_name = 'SEMUA STATUS KUPON';
                }elseif($status_kupon == '1') {
                    $status_kupon_name = 'PAKE KUPON';
                }elseif($status_kupon == '0') {
                    $status_kupon_name = 'TIDAK PAKE KUPON';
                }

                if ($status_pesan == 'all') {
                    $status_pesan_name = 'SEMUA STATUS PESANAN';
                }else{
                    $status_pesan_name = $status_pesan;
                }

                $report_top  = '<div class="row">
                                <div class="col-md-12">
                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Periode :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . MyHelper::date_id($periode_awal, 'j F Y', '') . ' s/d ' . MyHelper::date_id($periode_akhir, 'j F Y', '') . '</strong></div>

                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Nama Merchant :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . $tenant_name . '</strong></div>

                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Status Pesanan :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . $status_pesan_name . '</strong></div>
                                </div>
                            </div>'; 

                $report_data = '<thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Menu</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>';

                 if ($detail_pesanan->count() > 0) {
                    $no = 1;
                    foreach($detail_pesanan as $report){
                        $report_data .= '<tr>
                                            <td class="text-center">' . $no++ . '</td>
                                            <td>' . $report->namamenu . '</td>
                                            <td class="text-right">Rp. ' . number_format($report->harga,0,',','.') . '</td>
                                            <td class="text-right">' . $report->qty . '</td>
                                            <td class="text-right">Rp. ' . number_format($report->subtotal,0,',','.') . '</td>                                        
                                        </tr>';
                    }                        
                }

                $report_data .= '</body>';

                $response['status']     = 'success';
                $response['message']    = 'Data Laporan berhasil ditampilkan';
                $response['report']     = (object) array(
                    'top'               => $report_top,
                    'top_copy'          => (object) array(
                        'periode'           => "Periode :\n" . MyHelper::date_id($periode_awal, 'j F Y', '') . ' s/d ' . MyHelper::date_id($periode_akhir, 'j F Y', '') . "\n\n",
                        'tenant'            => "Nama Tenant/Toko :\n" . $tenant_name . "\n\n",
                        'kupon'             => "Status Kupon :\n" . $status_kupon_name . "\n\n",
                        'pesan'             => "Status Pesanan :\n" . $status_pesan_name . "\n\n",
                    ),
                    'data'              => $report_data,
                );


            } catch (QueryException $ex) {
                $response['status']     = 'fail';
                $response['message']    = 'Database Error! ' . $ex->getMessage();
            }

            return response()->json($response);
        }
    }

    public function laporanPesananDetailPdf(Request $request){

        try {

                $id           = $request->id;
                $periode_awal = $request->periode_awal;
                $periode_akhir= $request->periode_akhir;
                $tenant       = $request->tenant;
                $status_kupon = $request->status_kupon;
                $status_pesan = $request->status_pesan;

                $no           = 1;
                $reports      = DB::table('tbldetail_pesanan as a')
                                ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                                ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                                ->join('tblpengguna as d', 'b.idpemesan', 'd.idpengguna')
                                ->selectRaw('a.*, b.*, c.nama, d.namapengguna, c.kodetoko')
                                ->when($tenant!='all', function($query) use ($tenant){
                                    return $query->where('b.idtoko', $tenant);
                                })
                                ->when($status_kupon!='all', function($query) use ($status_kupon){
                                    if($status_kupon == 0){
                                        return $query->where('b.kupon', '');
                                    }elseif($status_kupon == 1){
                                        return $query->whereNotNull('b.kupon')->whereRaw('b.kupon <> ""');
                                    }
                                })
                                ->when($status_pesan!='all', function($query) use ($status_pesan){
                                    return $query->where('b.status_pesanan', $status_pesan);
                                })
                                ->whereBetween('b.tanggal', array($periode_awal,$periode_akhir))
                                ->where('a.nomorpesanan', $id)
                                ->get();

                if($tenant == 'all'){
                    $tenant_name = 'SEMUA TENANT/TOKO';
                }else{
                    $tenants = TblToko::where('kodetoko', $tenant)->first();
                    $tenant_name = $tenants->nama;
                }

                if ($status_kupon == 'all') {
                    $status_kupon_name = 'SEMUA STATUS KUPON';
                }elseif($status_kupon == '1') {
                    $status_kupon_name = 'PAKE KUPON';
                }elseif($status_kupon == '0') {
                    $status_kupon_name = 'TIDAK PAKE KUPON';
                }

                if ($status_pesan == 'all') {
                    $status_pesan_name = 'SEMUA STATUS PESANAN';
                }else{
                    $status_pesan_name = $status_pesan;
                }

            $pdf = PDF::loadView('laporan.data-pesanan.laporan-detail-pdf', compact('no', 'reports', 'tenant_name', 'status_pesan_name', 'periode_awal', 'periode_akhir'))->setPaper('legal', 'portrait');
            return $pdf->stream('Laporan Detail Pesanan.pdf');
        } catch (QueryException $ex) {
            return abort(405);
        }
    }

    public function indexPengguna(){
        $data = (object)array(
            'title'     => 'LAPORAN',
            'menu'      => 'laporan',
            'tanggal'   => now(), 
            'verify'    => TblPengguna::all(),
        );
        return view('laporan.daftar-pengguna.laporan', compact('data'));
    }

    public function tampilPengguna(Request $request){
        if ($request->isMethod('POST')) {
            try {
                $periode_awal = $request->periode_daftar_awal;
                $periode_akhir= $request->periode_daftar_akhir;

                $master_pengguna = TblPengguna::whereBetween('tglbergabung', array($periode_awal,$periode_akhir))                               
                                ->orderBy('tglbergabung', 'asc')                                
                                ->get();

                $report_top  = '<div class="row">
                                <div class="col-md-12">
                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Periode Daftar :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . MyHelper::date_id($periode_awal, 'j F Y', '') . ' s/d ' . MyHelper::date_id($periode_akhir, 'j F Y', '') . '</strong></div>
                                </div>
                            </div>'; 

                $report_data = '<thead>
                                    <tr>
                                        <th>No Urut</th>
                                        <th>Email</th>
                                        <th>Nama Pengguna</th>
                                        <th>Telepon</th>
                                        <th>WhatsApp</th>
                                        <th>alamat Pengiriman</th>
                                        <th>Tanggal Bergabung</th>
                                        <th>Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>';

                 if ($master_pengguna) {
                    $no = 1;
                    foreach($master_pengguna as $report){
                        $report_data .= '<tr>
                                            <td class="text-center">' . $no++ . '</td>
                                            <td>' . $report->emailpengguna . '</td>
                                            <td>' . $report->namapengguna . '</td>
                                            <td>' . $report->telp  . '</td>
                                            <td>' . $report->wa . '</td>
                                            <td>' . $report->alamatpengiriman . '</td>
                                            <td>' . MyHelper::date_id($report->tglbergabung, 'j F Y', '') . '</td>
                                            <td>' . $report->idverify . '</td>                                            
                                        </tr>';
                    }                        
                }

                $report_data .= '</body>';

                $response['status']     = 'success';
                $response['message']    = 'Data Laporan berhasil ditampilkan';
                $response['report']     = (object) array(
                    'top'               => $report_top,
                    'top_copy'          => (object) array(
                        'periode'           => "Periode Daftar :\n" . MyHelper::date_id($periode_awal, 'j F Y', '') . ' s/d ' . MyHelper::date_id($periode_akhir, 'j F Y', '') . "\n\n",
                    ),
                    'data'              => $report_data,
                    'recap_btn'         => '<button id="recap-btn" type="submit" class="btn btn-primary"><span class="fad fa-file-chart-line"></span> Cetak Laporan</button>'
                );


            } catch (QueryException $ex) {
                $response['status']     = 'fail';
                $response['message']    = 'Database Error! ' . $ex->getMessage();
            }

            return response()->json($response);
        }
    }

    public function laporanPenggunaPdf(Request $request){

        try {
            $periode_awal = $request->periode_daftar_awal;
            $periode_akhir= $request->periode_daftar_akhir;
            $no           = 1;

            $reports = TblPengguna::whereBetween('tglbergabung', array($periode_awal,$periode_akhir))
                        ->orderBy('tglbergabung', 'asc')
                        ->get();

            $pdf = PDF::loadView('laporan.daftar-pengguna.laporan-pdf', compact('no', 'reports', 'periode_awal', 'periode_akhir'))->setPaper('legal', 'landscape');
            return $pdf->stream('Laporan Daftar Pengguna.pdf');
        } catch (QueryException $ex) {
            return abort(405);
        }
    }

    public function tampilDetailPengguna(Request $request){
        if ($request->isMethod('POST')) {
            try {
                $id           = $request->id;
                $periode_awal = $request->periode_awal;
                $periode_akhir= $request->periode_akhir;
                $tenant       = $request->tenant;
                $status_kupon = $request->status_kupon;
                $status_pesan = $request->status_pesan;
                $status_bayar = $request->status_bayar;

                $detail_pesanan = DB::table('tbldetail_pesanan as a')
                                ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                                ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                                ->join('tblpengguna as d', 'b.idpemesan', 'd.idpengguna')
                                ->selectRaw('a.*, b.*, c.nama, d.namapengguna, c.kodetoko')
                                ->when($tenant!='all', function($query) use ($tenant){
                                    return $query->where('b.idtoko', $tenant);
                                })
                                ->when($status_kupon!='all', function($query) use ($status_kupon){
                                    if($status_kupon == 0){
                                        return $query->where('b.kupon', '');
                                    }elseif($status_kupon == 1){
                                        return $query->whereNotNull('b.kupon')->whereRaw('b.kupon <> ""');
                                    }
                                })
                                ->when($status_pesan!='all', function($query) use ($status_pesan){
                                    return $query->where('b.status_pesanan', $status_pesan);
                                })
                                ->when($status_bayar!='all', function($query) use ($status_bayar){
                                    return $query->where('b.status_bayar', $status_bayar);
                                })
                                ->whereBetween('b.tanggal', array($periode_awal,$periode_akhir))
                                ->where('a.nomorpesanan', $id)
                                ->get();

                if($tenant == 'all'){
                    $tenant_name = 'SEMUA TENANT/TOKO';
                }else{
                    $tenants = TblToko::where('kodetoko', $tenant)->first();
                    $tenant_name = $tenants->nama;
                }

                if ($status_kupon == 'all') {
                    $status_kupon_name = 'SEMUA STATUS KUPON';
                }elseif($status_kupon == '1') {
                    $status_kupon_name = 'PAKE KUPON';
                }elseif($status_kupon == '0') {
                    $status_kupon_name = 'TIDAK PAKE KUPON';
                }

                if ($status_pesan == 'all') {
                    $status_pesan_name = 'SEMUA STATUS PESANAN';
                }else{
                    $status_pesan_name = $status_pesan;
                }

                if ($status_bayar == 'all') {
                    $status_bayar_name = 'SEMUA STATUS PEMBAYARAN';
                }else{
                    $status_bayar_name = $status_bayar;
                }

                $report_top  = '<div class="row">
                                <div class="col-md-12">
                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Periode :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . MyHelper::date_id($periode_awal, 'j F Y', '') . ' s/d ' . MyHelper::date_id($periode_akhir, 'j F Y', '') . '</strong></div>

                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Nama Tenant/Toko :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . $tenant_name . '</strong></div>

                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Status Pesanan :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . $status_pesan_name . '</strong></div>

                                    <div class="text-dark-50 font-size-sm font-weight-bold mb-1">Status Pembayaran :</div>
                                    <div class="font-size-lg font-weight-bold mb-2"><strong>' . $status_bayar_name . '</strong></div>
                                </div>
                            </div>'; 

                $report_data = '<thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Tenant/Toko</th>
                                        <th>Nama Pemesan</th>
                                        <th>Nama Menu</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Sub Total</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>';

                 if ($detail_pesanan->count() > 0) {
                    $no = 1;
                    foreach($detail_pesanan as $report){
                        $report_data .= '<tr>
                                            <td class="text-center">' . $no++ . '</td>
                                            <td>' . MyHelper::date_id($report->tanggal) . '</td>
                                            <td>' . $report->nama  . '</td>
                                            <td>' . $report->namapengguna . '</td>
                                            <td>' . $report->namamenu . '</td>
                                            <td>' . $report->harga . '</td>
                                            <td>' . $report->qty . '</td>
                                            <td>' . $report->subtotal . '</td>
                                            <td>' . $report->keterangan . '</td>                                            
                                        </tr>';
                    }                        
                }

                $report_data .= '</body>';

                $response['status']     = 'success';
                $response['message']    = 'Data Laporan berhasil ditampilkan';
                $response['report']     = (object) array(
                    'top'               => $report_top,
                    'top_copy'          => (object) array(
                        'periode'           => "Periode :\n" . MyHelper::date_id($periode_awal, 'j F Y', '') . ' s/d ' . MyHelper::date_id($periode_akhir, 'j F Y', '') . "\n\n",
                        'tenant'            => "Nama Tenant/Toko :\n" . $tenant_name . "\n\n",
                        'kupon'             => "Status Kupon :\n" . $status_kupon_name . "\n\n",
                        'pesan'             => "Status Pesanan :\n" . $status_pesan_name . "\n\n",
                        'bayar'             => "Status Pembayaran :\n" . $status_bayar_name . "\n\n",
                    ),
                    'data'              => $report_data,
                );


            } catch (QueryException $ex) {
                $response['status']     = 'fail';
                $response['message']    = 'Database Error! ' . $ex->getMessage();
            }

            return response()->json($response);
        }
    }

    public function laporanPenggunaDetailPdf(Request $request){

        try {

                $id           = $request->id;
                $periode_awal = $request->periode_awal;
                $periode_akhir= $request->periode_akhir;
                $tenant       = $request->tenant;
                $status_kupon = $request->status_kupon;
                $status_pesan = $request->status_pesan;
                $status_bayar = $request->status_bayar;

                $no           = 1;
                $reports      = DB::table('tbldetail_pesanan as a')
                                ->join('tblmasterpesanan as b', 'a.nomorpesanan', 'b.nomorpesanan')
                                ->join('tbltoko as c', 'b.idtoko', 'c.kodetoko')
                                ->join('tblpengguna as d', 'b.idpemesan', 'd.idpengguna')
                                ->selectRaw('a.*, b.*, c.nama, d.namapengguna, c.kodetoko')
                                ->when($tenant!='all', function($query) use ($tenant){
                                    return $query->where('b.idtoko', $tenant);
                                })
                                ->when($status_kupon!='all', function($query) use ($status_kupon){
                                    if($status_kupon == 0){
                                        return $query->where('b.kupon', '');
                                    }elseif($status_kupon == 1){
                                        return $query->whereNotNull('b.kupon')->whereRaw('b.kupon <> ""');
                                    }
                                })
                                ->when($status_pesan!='all', function($query) use ($status_pesan){
                                    return $query->where('b.status_pesanan', $status_pesan);
                                })
                                ->when($status_bayar!='all', function($query) use ($status_bayar){
                                    return $query->where('b.status_bayar', $status_bayar);
                                })
                                ->whereBetween('b.tanggal', array($periode_awal,$periode_akhir))
                                ->where('a.nomorpesanan', $id)
                                ->get();

                if($tenant == 'all'){
                    $tenant_name = 'SEMUA TENANT/TOKO';
                }else{
                    $tenants = TblToko::where('kodetoko', $tenant)->first();
                    $tenant_name = $tenants->nama;
                }

                if ($status_kupon == 'all') {
                    $status_kupon_name = 'SEMUA STATUS KUPON';
                }elseif($status_kupon == '1') {
                    $status_kupon_name = 'PAKE KUPON';
                }elseif($status_kupon == '0') {
                    $status_kupon_name = 'TIDAK PAKE KUPON';
                }

                if ($status_pesan == 'all') {
                    $status_pesan_name = 'SEMUA STATUS PESANAN';
                }else{
                    $status_pesan_name = $status_pesan;
                }

                if ($status_bayar == 'all') {
                    $status_bayar_name = 'SEMUA STATUS PEMBAYARAN';
                }else{
                    $status_bayar_name = $status_bayar;
                }

            $pdf = PDF::loadView('laporan.data-pesanan.laporan-detail-pdf', compact('no', 'reports', 'tenant_name', 'status_pesan_name', 'status_bayar_name', 'periode_awal', 'periode_akhir'))->setPaper('legal', 'landscape');
            return $pdf->stream('Laporan Detail Pesanan.pdf');
        } catch (QueryException $ex) {
            return abort(405);
        }
    }
}
