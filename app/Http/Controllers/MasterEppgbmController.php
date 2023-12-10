<?php

namespace App\Http\Controllers;

use App\Models\MasterEppgbm;
use App\Models\MasterBalita;
use App\Models\MasterBbU;
use App\Models\MasterTbU;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

use DB;

class MasterEppgbmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Eppgbm/edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : RedirectResponse
    {
        //dd($request->all());
        $nik = $request->nik;
        $tgl_pengukuran = $request->tgl_pengukuran;

        $validated = $request->validate([
            'nik' => ['required','string', 'max:20'],
            'puskesmas.id' => ['required','integer'],
            'puskesmas.nama' => ['required','string', 'max:100'],
            'posyandu' => ['required','string', 'max:100'],
            'tgl_pengukuran' => [
                'date', 
                Rule::unique(MasterEppgbm::class)->where(function ($query) use($nik,$tgl_pengukuran) {
                    return $query->where('nik', $nik)->where('tgl_pengukuran', $tgl_pengukuran);
                }),
                function ($attribute, $value, $fail) use($request) {
                    $tgl_lahir = Carbon::parse($request->tgl_lahir);
                    $tgl_pengukuran = Carbon::parse($value);
                    if ($tgl_lahir->diffInMonths($tgl_pengukuran) > 60) {
                        $fail('Usia saat pengukuran lebih dari 5 tahun [' . $tgl_lahir->diff($tgl_pengukuran)->format('%y Tahun - %m Bulan - %d Hari').']');
                    }
                },
            ],
            'berat' => ['string', 'max:15'],
            'cara_ukur_tinggi' => ['required'],
            'tinggi' => ['string', 'max:15'],
        ]);

        $createData = $request->all();
        $createData['id_puskesmas'] = $request->puskesmas['id'];
        $createData['puskesmas'] = $request->puskesmas['nama'];
        $createData['usia_saat_ukur'] = Carbon::parse($request->tgl_lahir)->diff(Carbon::parse($request->tgl_pengukuran))->format('%y Tahun - %m Bulan - %d Hari');

        //hitung pengukuran
        $createData = $this->hitungBBTBU($createData);

        $crData = MasterEppgbm::create($createData);

        $this->updateLastPengukuran($createData['nik']);
        //dd($crData);
        //$encid = Crypt::encryptString($crData->id);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pengukuran Berhasil di Tambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterEppgbm $masterEppgbm, $id) : RedirectResponse
    {
        $nik = $request->nik;
        $tgl_pengukuran = $request->tgl_pengukuran;

        $validated = $request->validate([
            'nik' => ['required','string', 'max:20'],
            'puskesmas.id' => ['required','integer'],
            'puskesmas.nama' => ['required','string', 'max:100'],
            'posyandu' => ['required','string', 'max:100'],
            'tgl_pengukuran' => [
                'date', 
                Rule::unique(MasterEppgbm::class)->where(function ($query) use($nik,$tgl_pengukuran) {
                    return $query->where('nik', $nik)->where('tgl_pengukuran', $tgl_pengukuran);
                })->ignore($id),
                function ($attribute, $value, $fail) use($request) {
                    $tgl_lahir = Carbon::parse($request->tgl_lahir);
                    $tgl_pengukuran = Carbon::parse($value);
                    if ($tgl_lahir->diffInMonths($tgl_pengukuran) > 60) {
                        $fail('Usia saat pengukuran lebih dari 5 tahun [' . $tgl_lahir->diff($tgl_pengukuran)->format('%y Tahun - %m Bulan - %d Hari').']');
                    }
                },
            ],
            'berat' => ['string', 'max:15'],
            'tinggi' => ['string', 'max:15'],
            'cara_ukur_tinggi' => ['required'],
        ]);

        $updateData = $request->all();
        $updateData['id_puskesmas'] = $request->puskesmas['id'];
        $updateData['puskesmas'] = $request->puskesmas['nama'];

        //hitung pengukuran
        $updateData = $this->hitungBBTBU($updateData);

        $toUpdate = $masterEppgbm->find($id);
        $toUpdate->update($updateData);

        //update last pengukuran ke t_balita
        $this->updateLastPengukuran($toUpdate['nik']);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pengukuran Berhasil di Update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterEppgbm $masterEppgbm, $id) : RedirectResponse
    {
        $nid = Crypt::decryptString($id);
        $data = $masterEppgbm->find($nid);
        $nik = $data->nik;
        $data->delete();

        $this->updateLastPengukuran($nik);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pengukuran Berhasil di Dihapus'
        ]);
    }

    function hitungBBTBU($data)
    {
        $balita = MasterBalita::where('nik', '=', $data['nik'])->first();
        $diff = Carbon::parse($balita->tgl_lahir)->diff(Carbon::parse($data['tgl_pengukuran']));
        $usiabl = Carbon::parse($balita->tgl_lahir)->diffInMonths(Carbon::parse($data['tgl_pengukuran']));
        $data['usia_saat_ukur'] = $diff->format('%y Tahun - %m Bulan - %d Hari');

        $bb_u = MasterBbU::where('umur', '=', $usiabl)->where('jk', '=', $balita->jk)->first();
        //dd($bb_u);

        if($bb_u){
            if($data['berat'] < $bb_u->min3){
                $data['bb_u'] = "Sangat Kurang";
            }elseif($data['berat'] >= $bb_u->min3 and $data['berat'] < $bb_u->min2){
                $data['bb_u'] = "Kurang";
            }elseif($data['berat'] >= $bb_u->min2 and $data['berat'] < $bb_u->plus1){
                $data['bb_u'] = "Normal";
            }elseif($data['berat'] >= $bb_u->plus1){
                $data['bb_u'] = "Risiko Lebih";
            }    
        }else{
            $data['bb_u'] = "";
        }
        
        $tb_u = MasterTbU::where('umur', '=', $usiabl)->where('jk', '=', $balita->jk)->first();
        if($tb_u){
            if($data['tinggi'] < $tb_u->min3){
                $data['tb_u'] = "Sangat Pendek";
            }elseif($data['tinggi'] >= $tb_u->min3 and $data['tinggi'] < $tb_u->min2){
                $data['tb_u'] = "Pendek";
            }elseif($data['tinggi'] >= $tb_u->min2 and $data['tinggi'] <= $tb_u->plus3){
                $data['tb_u'] = "Normal";
            }elseif($data['tinggi'] > $tb_u->plus3){
                $data['tb_u'] = "Tinggi";
            }
        }else{
            $data['tb_u'] = "";
        }

        $chooseTable = $usiabl < 24 ? 'pb' : 'tb';

        $bb_pb_tb = DB::table("t_bb_".$chooseTable)
            ->where($chooseTable, '>=', $data['tinggi'])
            ->orderBy($chooseTable,'asc')
            ->first();

        if($bb_pb_tb){
            if($data['berat'] < $bb_pb_tb->min3){
                $data['bb_tb'] = "Gizi Buruk";
            }elseif($data['berat'] >= $bb_pb_tb->min3 and $data['berat'] < $bb_pb_tb->min2){
                $data['bb_tb'] = "Gizi Kurang";
            }elseif($data['berat'] >= $bb_pb_tb->min2 and $data['berat'] < $bb_pb_tb->plus1){
                $data['bb_tb'] = "Gizi Baik";
            }elseif($data['berat'] >= $bb_pb_tb->plus1 and $data['berat'] <= $bb_pb_tb->plus2){
                $data['bb_tb'] = "Resiko Gizi Lebih";
            }elseif($data['berat'] > $bb_pb_tb->plus2 and $data['berat'] <= $bb_pb_tb->plus3){
                $data['bb_tb'] = "Gizi Lebih";
            }elseif($data['berat'] > $bb_pb_tb->plus3){
                $data['bb_tb'] = "Obesitas";
            }
        }else{
            $data['bb_tb'] = "";
        }

        return $data;
    }

    function updateLastPengukuran($nik)
    {
        $pengukuran = MasterEppgbm::select('tgl_pengukuran', 'berat', 'tinggi')->where('nik', '=', $nik)->orderBy('tgl_pengukuran', 'desc')->first();
        //dd($pengukuran);
        MasterBalita::where('nik', '=', $nik)->update([
            'tgl_pengukuran' => $pengukuran->tgl_pengukuran,
            'berat' => $pengukuran->berat,
            'tinggi' => $pengukuran->tinggi,
        ]);
    }
}
