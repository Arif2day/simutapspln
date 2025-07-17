<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdiFakultas;
use App\Models\PeriodeKRS;
use App\Models\SurveiPertanyaan;
use App\Models\SurveiTipe;
use App\Models\SKMLayananDosen;
use App\Models\SKMLayananTendik;
use App\Models\SKMLayananPengelola;
use App\Models\SKMLayananSarpras;
use App\Models\SKMLayananSisfor;
use App\Models\SKMLayananKemahasiswaan;
use App\Models\SKMLayananPerpustakaan;
use App\Models\SKMKegiatanPembelajaran;
use PDF;
use iio\libmergepdf\Merger;

class PenelusuranPerkaraController extends Controller
{
    public function indexAll() {        
        $prodi = ProdiFakultas::all()->sortBy('nama_program_studi');
        $periode = PeriodeKRS::all()->sortByDesc('id');   
        $tipe = SurveiTipe::where('id','!=',8)->get()->sortBy('id');

        $return = array();
        return view("Guest.penelusuran-perkara.index", compact(['return','prodi','periode','tipe']));
    }

    public function indexPerdataUmum() {        
        $prodi = ProdiFakultas::all()->sortBy('nama_program_studi');
        $periode = PeriodeKRS::all()->sortByDesc('id');   
        $tipe = SurveiTipe::where('id','!=',8)->get()->sortBy('id');

        $return = array();
        return view("Guest.penelusuran-perkara.index-perdata-umum", compact(['return','prodi','periode','tipe']));
    }

    public static function prepareGraphData($data,$question,$respondent) {
        if (empty($data)) return [];
    
        // Ambil field dinamis antara 'id_prodi' dan 'created_at'
        $fieldKeys = array_keys($data[0]);
        $startIndex = array_search('id_prodi', $fieldKeys);
        $endIndex = array_search('created_at', $fieldKeys);
    
        if ($startIndex === false || $endIndex === false || $endIndex <= $startIndex) {
            return []; // Tidak valid
        }
    
        // Ambil kolom-kolom yang dibutuhkan secara dinamis
        $kolom = array_slice($fieldKeys, $startIndex + 1, $endIndex - $startIndex - 1);
        $hasil = [];
    
        foreach ($kolom as $kol) {
            $nilai = [];
    
            foreach ($data as $item) {
                if (isset($item[$kol])) {
                    $nilai[] = (float)$item[$kol];
                }
            }
    
            $jumlah = count($nilai);
            $rata = $jumlah > 0 ? array_sum($nilai) / $jumlah : 0;
    
            // Hitung standar deviasi
            $varian = 0;
            foreach ($nilai as $v) {
                $varian += pow($v - $rata, 2);
            }
            $stddev = $jumlah > 1 ? sqrt($varian / ($jumlah - 1)) : 0;
            
            $q = $question->filter(function ($item) use($kol) {
                return $item->question_code==$kol;
            })->values();
            $hasil[$kol] = [
                'rata_rata' => round($rata, 2),
                'lower' => round($rata-$stddev, 2),
                'upper' => round($rata+$stddev, 2),
                'standar_deviasi' => round($stddev, 2),
                'question' => $q
            ];
        }
    
        return ['responden'=>$respondent,'data'=>$hasil];
    }

    public static function getQsCode(array $data,$question)
    {
        $keys = array_keys($data);
        $start = array_search('id_prodi', $keys);
        $end = array_search('created_at', $keys);

        $res = null;
        // Ambil semua key di antara 'id_prodi' dan 'created_at'
        if ($start !== false && $end !== false && $end > $start) {
            $res =  array_slice($keys, $start + 1, $end - $start - 1);
        }

        $result = array();
        foreach($res as $val){
            $temp = array();
            $q = $question->filter(function ($item) use($val) {
                return $item->question_code==$val;
            })->values()[0]['question'];
            $temp['kode']=$val;
            $temp['qc']=$q;
            array_push($result,$temp);
        }
        return $result;
    }

    public function getDataSurvei(Request $req){
        $res['error']=false;
        $res['data']=array();
        $res['message']="";

        // Get request value
        $id_prodi = $req->id_prodi;
        $id_semester = $req->id_semester;
        $tipe_survei = $req->tipe_survei;
        try {
            switch ($tipe_survei) {
                case 1:
                    $qstn = SurveiPertanyaan::where('id_survei_tipe',1)->get();
                    $q = SKMLayananDosen::where('id_semester',$id_semester);
                    if($id_prodi!=='all'){
                        $q->where('id_prodi',$id_prodi);
                    }
                    $pdata = $q->get()->toArray();
                    $res['data'] = $this->prepareGraphData($pdata,$qstn,count($pdata));
                    break;
                case 2:
                    $qstn = SurveiPertanyaan::where('id_survei_tipe',2)->get();
                    $q = SKMLayananTendik::where('id_semester',$id_semester);
                    if($id_prodi!=='all'){
                        $q->where('id_prodi',$id_prodi);
                    }
                    $pdata = $q->get()->toArray();
                    $res['data'] = $this->prepareGraphData($pdata,$qstn,count($pdata));
                    break;                
                case 3:
                    $qstn = SurveiPertanyaan::where('id_survei_tipe',3)->get();
                    $q = SKMLayananPengelola::where('id_semester',$id_semester);
                    if($id_prodi!=='all'){
                        $q->where('id_prodi',$id_prodi);
                    }
                    $pdata = $q->get()->toArray();
                    $res['data'] = $this->prepareGraphData($pdata,$qstn,count($pdata));
                    break;
                case 4:
                    $qstn = SurveiPertanyaan::where('id_survei_tipe',4)->get();
                    $q = SKMLayananSarpras::where('id_semester',$id_semester);
                    if($id_prodi!=='all'){
                        $q->where('id_prodi',$id_prodi);
                    }
                    $pdata = $q->get()->toArray();
                    $res['data'] = $this->prepareGraphData($pdata,$qstn,count($pdata));
                    break;
                case 5:
                    $qstn = SurveiPertanyaan::where('id_survei_tipe',5)->get();
                    $q = SKMLayananSisfor::where('id_semester',$id_semester);
                    if($id_prodi!=='all'){
                        $q->where('id_prodi',$id_prodi);
                    }
                    $pdata = $q->get()->toArray();
                    $res['data'] = $this->prepareGraphData($pdata,$qstn,count($pdata));
                    break;
                case 6:
                    $qstn = SurveiPertanyaan::where('id_survei_tipe',6)->get();
                    $q = SKMLayananKemahasiswaan::where('id_semester',$id_semester);
                    if($id_prodi!=='all'){
                        $q->where('id_prodi',$id_prodi);
                    }
                    $pdata = $q->get()->toArray();
                    $res['data'] = $this->prepareGraphData($pdata,$qstn,count($pdata));
                    break;
                case 7:
                    $qstn = SurveiPertanyaan::where('id_survei_tipe',7)->get();
                    $q = SKMLayananPerpustakaan::where('id_semester',$id_semester);
                    if($id_prodi!=='all'){
                        $q->where('id_prodi',$id_prodi);
                    }
                    $pdata = $q->get()->toArray();
                    $res['data'] = $this->prepareGraphData($pdata,$qstn,count($pdata));
                    break;
                default:
                    $res['data'] = array();
                    break;
            }
        }catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }   

        return response()->json($res);
    }

    public function cetak(Request $req) {
        $data = $req->chartData;
        $title = $req->chartTitle;
        $filter = json_decode($req->chartFilter,true); 
        $qstn = null;       
        $pdata = null;       
        
        switch ($filter[2]) {
            case 1:
                $q = SKMLayananDosen::where('id_semester',$filter[1])->with(['user']);
                if($filter[0]!=='all'){
                    $q->where('id_prodi',$filter[0]);
                }
                $pdata = $q->get()->toArray();
                $qstn = SurveiPertanyaan::where('id_survei_tipe',1)->get();
                break;
            case 2:
                $q = SKMLayananTendik::where('id_semester',$filter[1])->with(['user']);
                if($filter[0]!=='all'){
                    $q->where('id_prodi',$filter[0]);
                }
                $pdata = $q->get()->toArray();
                $qstn = SurveiPertanyaan::where('id_survei_tipe',2)->get();
                break;                
            case 3:
                $q = SKMLayananPengelola::where('id_semester',$filter[1])->with(['user']);
                if($filter[0]!=='all'){
                    $q->where('id_prodi',$filter[0]);
                }
                $pdata = $q->get()->toArray();
                $qstn = SurveiPertanyaan::where('id_survei_tipe',3)->get();
                break;
            case 4:
                $q = SKMLayananSarpras::where('id_semester',$filter[1])->with(['user']);
                if($filter[0]!=='all'){
                    $q->where('id_prodi',$filter[0]);
                }
                $pdata = $q->get()->toArray();
                $qstn = SurveiPertanyaan::where('id_survei_tipe',4)->get();
                break;
            case 5:
                $q = SKMLayananSisfor::where('id_semester',$filter[1])->with(['user']);
                if($filter[0]!=='all'){
                    $q->where('id_prodi',$filter[0]);
                }
                $pdata = $q->get()->toArray();
                $qstn = SurveiPertanyaan::where('id_survei_tipe',5)->get();
                break;
            case 6:
                $q = SKMLayananKemahasiswaan::where('id_semester',$filter[1])->with(['user']);
                if($filter[0]!=='all'){
                    $q->where('id_prodi',$filter[0]);
                }
                $pdata = $q->get()->toArray();
                $qstn = SurveiPertanyaan::where('id_survei_tipe',6)->get();
                break;                
            default:
                $q = SKMLayananPerpustakaan::where('id_semester',$filter[1])->with(['user']);
                if($filter[0]!=='all'){
                    $q->where('id_prodi',$filter[0]);
                }
                $pdata = $q->get()->toArray();
                $qstn = SurveiPertanyaan::where('id_survei_tipe',7)->get();
                break;
        }
        
        
        $m = new Merger();

        $pdf1 = PDF::loadView('Guest.penelusuran-perkara.temp',compact('data','title'));
        $pdf1->setPaper('f4', 'landscape');
        $m->addRaw($pdf1->output());

        // dd(['prepared_data'=>$this->prepareGraphData($pdata,$qstn,count($pdata)),'raw'=>$pdata,'title'=>$filter[3],'QsCode'=>$this->getQsCode($pdata[0],$qstn)]);
        // PDF kedua (misalnya orientasi landscape)
        $pdf2 = PDF::loadView('Guest.penelusuran-perkara.temp2',['prepared_data'=>$this->prepareGraphData($pdata,$qstn,count($pdata)),'raw'=>$pdata,'title'=>$filter[3],'QsCode'=>$this->getQsCode($pdata[0],$qstn)]);
        $pdf2->setPaper('f4', 'portrait');
        $m->addRaw($pdf2->output()); // tambahkan juga

        $pdf3 = PDF::loadView('Guest.penelusuran-perkara.temp3',['prepared_data'=>$this->prepareGraphData($pdata,$qstn,count($pdata)),'raw'=>$pdata,'title'=>$filter[3],'QsCode'=>$this->getQsCode($pdata[0],$qstn)]);
        $pdf3->setPaper('f4', 'landscape');
        $m->addRaw($pdf3->output()); // tambahkan juga
        
        // file_put_contents('combined.pdf', $m->merge());

        // return $pdf1->download('grafik-survei.pdf');
        return response($m->merge())
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="grafik-survei.pdf"');
    }
}
