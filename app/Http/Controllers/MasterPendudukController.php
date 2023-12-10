<?php

namespace App\Http\Controllers;

use App\Models\MasterPenduduk;
use App\Models\MasterBalita;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

use DB;

class MasterPendudukController extends Controller
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
        ->with(['balita', 'asuh'])
        ->leftjoin('t_kecamatan', 't_kecamatan.id', 'kec')
        ->leftjoin('t_kelurahan', function($join){
            $join->on('t_kelurahan.id', '=', 'desa_kel');
            $join->on('t_kelurahan.id_kecamatan', '=', 'kec');
        })->select('t_penduduk.id as id', 't_penduduk.nik', 't_penduduk.nama', 't_penduduk.pekerjaan', 't_penduduk.instansi', 't_penduduk.alamat', 't_penduduk.rt', 't_penduduk.rw', 't_penduduk.kec', 't_kecamatan.nama as nama_kec', 't_penduduk.desa_kel', 't_kelurahan.nama as nama_desa_kel')
        ->paginate($request->perPage ?? 20)->withQueryString();

        return Inertia::render('Penduduk/index', [
            'njns'   => 'Penduduk',
            'croute' => 'penduduk',
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
            'njns'   => 'Penduduk',
            'croute' => 'penduduk',
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
    public function store(MasterPenduduk $masterPenduduk, Request $request) : RedirectResponse
    {
        //dd($request->all());
        //$id = $request->id;

        $validated = $request->validate([
            'nik' => ['nullable','max:255', Rule::unique(MasterPenduduk::class)],
            'nama' => ['required','string', 'max:255'],
            'pekerjaan' => ['max: 100'],
            'instansi' => ['max: 200'],
            'prov' => ['max:100'],
            'kab_kota' => ['max:100'],
            'kec' => ['max:100'],
            'desa_kel' => ['max:100'],
            'rt' => ['max:5'],
            'rw' => ['max:5'],
            'alamat' => ['max:255'],
        ]);

        $createData = $request->all();
        $createData['id'] = Str::uuid()->toString();
        $masterPenduduk->create($createData);

        $encid = Crypt::encryptString($createData['id']);

        return Redirect::route('penduduk.edit', ['id' => $encid])->with([
            'type'      => 'success',
            'message'   => 'Data Penduduk Berhasil di Tambahkan'
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
    public function edit(MasterPenduduk $masterPenduduk, $id)
    {
        $nid = Crypt::decryptString($id);
        
        return Inertia::render('Penduduk/edit', [
            'data' => $masterPenduduk->with(['balita','asuh', 'asuh.balita', 'sanitasi'])->find($nid),
            'njns'   => 'Penduduk',
            'croute' => 'penduduk',
            'masterBalita' => MasterBalita::all(),
            'masterKecamatan' => MasterKecamatan::all()->pluck('nama', 'id'),
            'masterKelurahan' => MasterKelurahan::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MasterPenduduk $masterPenduduk, Request $request, $id) : RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['nullable','max:255', Rule::unique(MasterPenduduk::class)->ignore($id)],
            'nama' => ['max:255'],
            'pekerjaan' => ['max: 100'],
            'instansi' => ['max: 200'],
            'prov' => ['max:100'],
            'kab_kota' => ['max:100'],
            'kec' => ['max:100'],
            'desa_kel' => ['max:100'],
            'rt' => ['max:5'],
            'rw' => ['max:5'],
            'alamat' => ['max:255'],
        ]);

        $toUpdate = $masterPenduduk->find($id);
        $toUpdate->update($request->all());

        if($request->id_balita){
            $balita = [
                'id' => $request->id_balita,
                'id_ortu' => $request->id,
                'nama_ortu' => $request->nama,
            ]; 

            MasterBalita::find($balita['id'])->update($balita);    
        }

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Penduduk Berhasil di Update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterPenduduk $masterPenduduk, $id)
    {
        $nid = Crypt::decryptString($id);
        //dd($nid);
        $data = $masterPenduduk->find($nid);
        $jns = $data->jns ?? 'ortu';
        if($jns == 'asuh'){
            //dd($data);
            $data->jns = "";
            $data->save();
        }else{
            //dd($data);
            $data->delete();
        }

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Penduduk Berhasil di Dihapus'
        ]);
    }
}
