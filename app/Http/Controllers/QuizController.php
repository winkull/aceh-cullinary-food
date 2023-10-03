<?php

namespace App\Http\Controllers;

use App\Models\TblQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\MyHelper;

class QuizController extends Controller
{
    public function index(){
        $data = (object)array(
            'title'     => 'QUIZ',
            'menu'      => 'quiz',       
        );
        return view('quiz.quiz', compact('data'));
    }

    public function Create(){
        $data = (object)array(
            'title'     => 'TAMBAH QUIZ',
            'menu'      => 'quiz',
            'tanggal'   => now(),
        );

        return view('quiz.add', compact('data'));
    }

    public function Insert(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblQuiz::where('petunjuk', $request->petunjuk)->first();
                if($dataExists)
                {
                    return back()->with("fail", "petunjuk quiz <strong>" . $request->petunjuk . "</strong> sudah terdaftar")->withInput();
                }

                $data                   = new TblQuiz();
                if ($request->petunjuk == '') {
                    $data->petunjuk = '-';
                }else{
                    $data->petunjuk  = $request->petunjuk;
                }
                $data->tanggal_mulai    = $request->mulai;
                $data->tanggal_selesai  = $request->selesai;
                $data->aktif            = $request->status;                
                $data->save();                

                return redirect()->route("quiz")->with("success", "Berhasil tambah data quiz <strong>" . $data->petunjuk . "</strong>");
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal tambah data quiz " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function Data(Request $request){
        $columns = array(
            1 => 'petunjuk',
            2 => 'tanggal_mulai',
            3 => 'tanggal_selesai',
            4 => 'aktif',

        );

        $totalData = TblQuiz::count();

        $totalFiltered = $totalData;

        $limit  = $request->input('length');
        $start  = $request->input('start');
        $order  = $columns[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $datas = TblQuiz::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }
        else
        {
            $search = $request->input('search.value');

            $datas = TblQuiz::where(function ($query) use ($search){
                        $query->orWhere('petunjuk','LIKE',"%{$search}%")
                            ->orWhere('tanggal_mulai','LIKE',"%{$search}%")
                            ->orWhere('tanggal_selesai','LIKE',"%{$search}%")
                            ->orWhere('aktif','LIKE',"%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

            $totalFiltered = 
            $datas = TblQuiz::where(function ($query) use ($search){
                            $query->orWhere('petunjuk','LIKE',"%{$search}%")
                            ->orWhere('tanggal_mulai','LIKE',"%{$search}%")
                            ->orWhere('tanggal_selesai','LIKE',"%{$search}%")
                            ->orWhere('aktif','LIKE',"%{$search}%");
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
                $nestedData['id']          = [$item->id . ',' . $item->petunjuk . ',' . route("quiz.edit", ['id' => $item->id]) . ',' . route("quiz.delete", ['id' => $item->id])];
                $nestedData['petunjuk']    = $item->petunjuk;
                $nestedData['mulai']       = MyHelper::date_id($item->tanggal_mulai);
                $nestedData['selesai']     = MyHelper::date_id($item->tanggal_selesai);
                $nestedData['status']      = $item->aktif == '1' ? '<center><small><label class="bg-info text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Aktif</label></small></center>': '<center><small><label class="bg-danger text-white" style="padding-top: 5px; padding-bottom: 5px; padding-right: 15px; padding-left: 15px; border-radius: 10px">Tidak Aktif</label></small></center>';

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
                'title'     => 'EDIT QUIZ',
                'menu'      => 'quiz',
            );
            $datas = TblQuiz::where('id', $id)->first();           

            return view('quiz.edit', compact('data','datas'));
        } catch (QueryException $ex) {
            return abort(500);
        }
    }    
    
    public function Update(Request $request){
        if($request->isMethod("POST"))
        {
            try{
                $dataExists = TblQuiz::where('id', '<>',$request->id)->where('petunjuk', $request->petunjuk)->first();
                if($dataExists)
                {
                    return back()->with("fail", "Quiz <strong>" . $request->petunjuk . "</strong> sudah ada")->withInput();
                }

                $data                   = TblQuiz::where('id', $request->id)->first();
                if ($request->petunjuk == '') {
                    $data->petunjuk = '-';
                }else{
                    $data->petunjuk  = $request->petunjuk;
                }
                $data->tanggal_mulai    = $request->mulai;
                $data->tanggal_selesai  = $request->selesai;
                $data->aktif            = $request->status;                
                $data->save();
                return redirect()->route("quiz")->with("success", "Berhasil ubah data quiz <strong>" . $data->petunjuk . "</strong>");
                
            }catch (QueryException $ex){
                return back()->with("fail", "Gagal ubah data quiz " . $ex->getMessage())->withInput();
            }
        }
        else
        {
            return abort(405);
        }
    }

    public function Delete(Request $request){
        try{
            $data = TblQuiz::where('id', $request->id)->first();
            if($data)
            {
                if($data->delete())
                    {
                        return redirect()->route("quiz")->with("success", "Berhasil hapus data quiz <strong>" . $data->petunjuk . "</strong>");
                    }
                    else
                    {
                        return redirect()->route("quiz")->with("fail", "Gagal hapus quiz <strong>" . $data->petunjuk . "</strong>, coba lagi!");
                    }
            }
            else
            {
                return back()->with("fail", "Tidak ditemukan data quiz");
            }
        }catch (QueryException $ex) {
            return back()->with("fail", "Gagal hapus data quiz" . $ex->getMessage());
        }
    }
}
