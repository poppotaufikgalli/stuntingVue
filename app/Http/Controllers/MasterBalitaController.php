<?php

namespace App\Http\Controllers;

use App\Models\MasterBalita;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;

//use App\Models\MasterEppgbm;
//use App\Models\MasterPemeriksaan;

use App\Exports\BalitaExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\MasterPenduduk;
use App\Models\MasterPendudukAsuh;
use App\Models\MasterPuskesmas;
use App\Models\MasterBantuan;
use App\Models\MasterInfoGizi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

use DB;

class MasterBalitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : Response
    {   
        $stbu = $request->stbu;
        $listBalita = MasterBalita::when($request->search, function($query, $search){
            $query->where('t_balita.nama', 'LIKE', '%'.$search.'%')->orWhere('t_balita.nik', 'LIKE', '%'.$search.'%');
        })
        ->leftjoin('t_kelurahan', function($join){
            $join->on('t_kelurahan.id', '=', 'desa_kel');
            $join->on('t_kelurahan.id_kecamatan', '=', 'kec');
        })
        ->select('t_balita.id as id', 't_balita.nik', 't_balita.nama', 't_balita.nama_ortu', 't_balita.tgl_lahir', 't_balita.jk', 't_balita.kec', 't_balita.desa_kel', 't_kelurahan.nama as nama_desa_kel', 't_balita.id_ortu')
        ->with(['kecamatan', 'pengukuran_terakhir'])
        ->whereHas('pengukuran_terakhir', function ($query) use ($stbu) {
            if($stbu != ""){
                $query->where('tb_u', "=", $stbu);    
            }
        })
        ->paginate($request->perPage ?? 10)->withQueryString();
        //dd($listBalita);
        $listFilter = MasterInfoGizi::all();
        
        return Inertia::render('Balita/index', [
            'listBalita' => $listBalita,
            'listFilter' => $listFilter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Balita/edit', [
            'masterOrtu' => MasterPenduduk::all(),
            'masterKecamatan' => MasterKecamatan::all()->pluck('nama', 'id'),
            'masterKelurahan' => MasterKelurahan::all(),
        ]);
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

        $validated = $request->validate([
            'nik' => ['required','string', 'max:255', Rule::unique(MasterBalita::class)],
            'nama' => ['required','string', 'max:255'],
            'jk' => ['required','string', 'max:3'],
            'tgl_lahir' => ['required','date'],
            'bb_lahir' => ['string', 'max:15'],
            'tb_lahir' => ['string', 'max:15'],
            'tgl_pengukuran' => ['date'],
            'berat' => ['string', 'max:15'],
            'tinggi' => ['string', 'max:15'],
            'nama_ortu' => ['required', 'string', 'max:36'],
            'nik_ortu' => ['string', 'max:255'],
            'prov' => ['required','string', 'max:100'],
            'kab_kota' => ['required','string', 'max:100'],
            'kec' => ['required','string', 'max:100'],
            'desa_kel' => ['required','string', 'max:100'],
            'rt' => ['required','string', 'max:5'],
            'rw' => ['required','string', 'max:5'],
            'alamat' => ['required','string', 'max:255'],
        ]);

        //$this->dataOrangTua($request);

        $crData = MasterBalita::create($request->all());
        //dd($crData);
        $encid = Crypt::encryptString($crData->id);

        return Redirect::route('balita.edit', ['id' => $encid])->with([
            'type'      => 'success',
            'message'   => 'Data Balita Berhasil di Tambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterBalita  $masterBalita
     * @return \Illuminate\Http\Response
     */
    public function show(MasterBalita $masterBalita)
    {
        return Excel::download(new BalitaExport, 'Balita.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterBalita  $masterBalita
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterBalita $masterBalita, $id) : Response
    {
        $nid = Crypt::decryptString($id);
        $dataBalita = $masterBalita->with(['pengukuran', 'ortu', 'ortu_asuh', 'ortu_asuh.penduduk', 'bantuan'])->find($nid);

        return Inertia::render('Balita/edit', [
            'data' => $dataBalita,
            'masterOrtu' => MasterPenduduk::all(),
            'masterKecamatan' => MasterKecamatan::all()->pluck('nama', 'id'),
            'masterKelurahan' => MasterKelurahan::all(),
            'masterPuskesmas' => MasterPuskesmas::select('id', 'nama')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterBalita  $masterBalita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterBalita $masterBalita, $id) : RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required','string', 'max:255', Rule::unique(MasterBalita::class)->ignore($id)],
            'nama' => ['string', 'max:255'],
            'jk' => ['string', 'max:3'],
            'tgl_lahir' => ['date'],
            'bb_lahir' => ['string', 'max:15'],
            'tb_lahir' => ['string', 'max:15'],
            'tgl_pengukuran' => ['date'],
            'berat' => ['max:15'],
            'tinggi' => ['max:15'],
            'prov' => ['max:100'],
            'kab_kota' => ['max:100'],
            'kec' => ['max:100'],
            'desa_kel' => ['max:100'],
            'rt' => ['max:5'],
            'rw' => ['max:5'],
            'alamat' => ['max:255'],
        ]);

        //$this->dataOrangTua($request);

        $toUpdate = $masterBalita->find($id);
        $toUpdate->update($request->all());

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Balita Berhasil di Update'
        ]);
    }

    function dataOrangTua(Request $request){
        $retID = $request->id_ortu;
        $data = $request->only('alamat', 'rt', 'rw', 'desa_kel', 'kec', 'kab_kota', 'prov');
        $data['nik'] = $request->nik_ortu;
        $data['nama'] = $request->nama_ortu;
        if($data['nik'] != ""){
            $dataPenduduk = MasterPenduduk::where('nik', '=', $data['nik'])->first();
            if($dataPenduduk == null){
                $retID = DB::table('t_penduduk')->insertGetId($dataPenduduk);
            }
        }else{
            $dataPenduduk = MasterPenduduk::find($retID);
            //dd([$dataPenduduk, '2']);
        }
        //dd($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterBalita  $masterBalita
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterBalita $masterBalita)
    {
        //
    }
}
