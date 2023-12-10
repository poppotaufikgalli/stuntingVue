<?php

namespace App\Http\Controllers;

use App\Models\MasterPendudukAsuh;
use App\Models\MasterPenduduk;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;
use App\Models\MasterBalita;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

use DB;

class MasterPendudukAsuhController extends Controller
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
        ->join('t_penduduk_asuh', 't_penduduk_asuh.id_penduduk', 't_penduduk.id')
        ->leftjoin('t_kecamatan', 't_kecamatan.id', 't_penduduk.kec')
        ->leftjoin('t_kelurahan', function($join){
            $join->on('t_kelurahan.id', '=', 't_penduduk.desa_kel');
            $join->on('t_kelurahan.id_kecamatan', '=', 't_penduduk.kec');
        })->select('t_penduduk.id as id', 't_penduduk.nik', 't_penduduk.nama', 't_penduduk.pekerjaan', 't_penduduk.instansi', 't_penduduk.alamat', 't_penduduk.rt', 't_penduduk.rw', 't_penduduk.kec', 't_kecamatan.nama as nama_kec', 't_penduduk.desa_kel', 't_kelurahan.nama as nama_desa_kel')
        ->groupBy('t_penduduk.id')
        ->with(['balita', 'asuh'])
        ->paginate(20)->withQueryString();

        return Inertia::render('Penduduk/index', [
            'njns' => 'Orang Tua Asuh',
            'croute' => 'asuh',
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
            'njns' => 'Orang Tua Asuh',
            'croute' => 'asuh',
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
            'penduduk.id' => ['required','string', 'max:36'],
            'nik_balita' => ['required','string', 'max:20'],
            'tgl_asuh' => ['required','date'],
            'status_asuh' => ['required'],
        ]);

        $createData = $request->all();
        $createData['id_penduduk'] = $createData['penduduk']['id'];
        //dd($createData);

        //$this->dataOrangTua($request);

        $crData = MasterPendudukAsuh::create($createData);
        //dd($crData);
        //$encid = Crypt::encryptString($crData->id);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Orang Tua Asuh Berhasil di Tambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterPendudukAsuh  $masterPendudukAsuh
     * @return \Illuminate\Http\Response
     */
    public function show(MasterPendudukAsuh $masterPendudukAsuh)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterPendudukAsuh  $masterPendudukAsuh
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterPendudukAsuh $masterPendudukAsuh, $id)
    {
        $nid = Crypt::decryptString($id);
        
        return Inertia::render('Penduduk/edit', [
            'njns' => 'Orang Tua Asuh',
            'croute' => 'asuh',
            //'data' => $masterPendudukAsuh->with(['balita','asuh', 'asuh.balita', 'sanitasi'])->find($nid),
            'data' => MasterPenduduk::with(['balita','asuh', 'asuh.balita', 'sanitasi'])->find($nid),
            'masterBalita' => MasterBalita::all(),
            'masterKecamatan' => MasterKecamatan::all()->pluck('nama', 'id'),
            'masterKelurahan' => MasterKelurahan::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterPendudukAsuh  $masterPendudukAsuh
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterPendudukAsuh $masterPendudukAsuh, $id) : RedirectResponse
    {
        $validated = $request->validate([
            'penduduk.id' => ['required','string', 'max:36'],
            'nik_balita' => ['required','string', 'max:20'],
            'tgl_asuh' => ['required','date'],
            'status_asuh' => ['required'],
        ]);

        //$this->dataOrangTua($request);

        $updateData = $request->all();
        $updateData['id_penduduk'] = $updateData['penduduk']['id'];

        $toUpdate = $masterPendudukAsuh->find($id);
        $toUpdate->update($updateData);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Orang Tua Asuh Berhasil di Update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterPendudukAsuh  $masterPendudukAsuh
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterPendudukAsuh $masterPendudukAsuh, $id)
    {
        //dd($id);
        $nid = Crypt::decryptString($id);
        $data = $masterPendudukAsuh->find($nid);
        if($data){
            $data->delete();    
        }

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Balita Asuh Berhasil di Dihapus'
        ]);
    }
}
