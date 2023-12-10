<?php

namespace App\Http\Controllers;

use App\Models\MasterHamil;
use App\Models\MasterPenduduk;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;
use App\Models\MasterPuskesmas;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

class MasterHamilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : Response
    {
        $listPenduduk = MasterPenduduk::when($request->search, function($query, $search){
            $query->where('t_penduduk.nama', 'LIKE', '%'.$search.'%')->orWhere('t_penduduk.nik', 'LIKE', '%'.$search.'%');
        })
        ->join('t_hamil', 't_hamil.id_penduduk', 't_penduduk.id')
        ->leftjoin('t_kecamatan', 't_kecamatan.id', 't_penduduk.kec')
        ->leftjoin('t_kelurahan', function($join){
            $join->on('t_kelurahan.id', '=', 't_penduduk.desa_kel');
            $join->on('t_kelurahan.id_kecamatan', '=', 't_penduduk.kec');
        })->select('t_penduduk.id as id', 't_penduduk.nik', 't_penduduk.nama', 't_penduduk.pekerjaan', 't_penduduk.instansi', 't_penduduk.alamat', 't_penduduk.rt', 't_penduduk.rw', 't_penduduk.kec', 't_kecamatan.nama as nama_kec', 't_penduduk.desa_kel', 't_kelurahan.nama as nama_desa_kel')
        ->with('hamil_terakhir')
        ->groupBy('t_penduduk.id')
        ->paginate($request->perPage ?? 10)->withQueryString();

        //dd($listPenduduk);

        return Inertia::render('Penduduk/index', [
            'njns' => 'Ibu Hamil',
            'croute' => 'hamil',
            'listPenduduk' => $listPenduduk,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Penduduk/edit', [
            'njns' => 'Ibu Hamil',
            'croute' => 'hamil',
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
    public function store(MasterHamil $masterHamil, Request $request): RedirectResponse
    {
        //dd($request->all());
        //$id = $request->id;

        $validated = $request->validate([
            'id_penduduk' => ['required','max:36'],
            'hamil_ke' => ['required','integer'],
            'anak_ke' => ['required','integer'],
            'tgl_pengukuran' => ['required', 'date'],
            'usia_kehamilan' => ['required', 'integer'],
        ]);

        $createData = $request->all();
        $createData['id_puskesmas'] = $request->puskesmas['id'];
        $createData['puskesmas'] = $request->puskesmas['nama'];

        $masterHamil->create($createData);

        //$encid = Crypt::encryptString($createData['id']);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pemeriksaan Kehamilam Berhasil di Tambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterHamil  $masterHamil
     * @return \Illuminate\Http\Response
     */
    public function show(MasterHamil $masterHamil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterHamil  $masterHamil
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterHamil $masterHamil, $id) : Response
    {
        $nid = Crypt::decryptString($id);
        
        return Inertia::render('Penduduk/edit', [
            'njns' => 'Ibu Hamil',
            'croute' => 'hamil',
            'data' => MasterPenduduk::with('hamil')->find($nid),
            //'masterBalita' => MasterBalita::all(),
            'masterPuskesmas' => MasterPuskesmas::all(),
            'masterKecamatan' => MasterKecamatan::all()->pluck('nama', 'id'),
            'masterKelurahan' => MasterKelurahan::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterHamil  $masterHamil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterHamil $masterHamil, $id): RedirectResponse
    {
        $validated = $request->validate([
            'id_penduduk' => ['required','max:36'],
            'hamil_ke' => ['required','integer'],
            'anak_ke' => ['required','integer'],
            'tgl_pengukuran' => ['required', 'date'],
            'usia_kehamilan' => ['required', 'integer'],
        ]);

        //$this->dataOrangTua($request);

        $updateData = $request->all();
        $updateData['id_puskesmas'] = $request->puskesmas['id'];
        $updateData['puskesmas'] = $request->puskesmas['nama'];

        $toUpdate = $masterHamil->find($id);
        $toUpdate->update($updateData);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pemeriksaan Kehamilam Berhasil di Update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterHamil  $masterHamil
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterHamil $masterHamil, $id)
    {
        $nid = Crypt::decryptString($id);
        $data = $masterHamil->find($nid);
        if($data){
            $data->delete();    
        }

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pemeriksaan Kehamilam Berhasil di Dihapus'
        ]);
    }
}
