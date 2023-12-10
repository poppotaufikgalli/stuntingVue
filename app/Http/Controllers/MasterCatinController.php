<?php

namespace App\Http\Controllers;

use App\Models\MasterCatin;
use App\Models\MasterPenduduk;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;
use App\Models\MasterPuskesmas;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class MasterCatinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : Response
    {
        //return app(\App\Http\Controllers\MasterPendudukController::class)->index($request, 'remaja');
        $listPenduduk = MasterPenduduk::when($request->search, function($query, $search){
            $query->where('t_penduduk.nama', 'LIKE', '%'.$search.'%')->orWhere('t_penduduk.nik', 'LIKE', '%'.$search.'%');
        })
        ->join('t_catin', 't_catin.id_penduduk', 't_penduduk.id')
        ->leftjoin('t_kecamatan', 't_kecamatan.id', 't_penduduk.kec')
        ->leftjoin('t_kelurahan', function($join){
            $join->on('t_kelurahan.id', '=', 't_penduduk.desa_kel');
            $join->on('t_kelurahan.id_kecamatan', '=', 't_penduduk.kec');
        })->select('t_penduduk.id as id', 't_penduduk.nik', 't_penduduk.nama', 't_penduduk.pekerjaan', 't_penduduk.instansi', 't_penduduk.alamat', 't_penduduk.rt', 't_penduduk.rw', 't_penduduk.kec', 't_kecamatan.nama as nama_kec', 't_penduduk.desa_kel', 't_kelurahan.nama as nama_desa_kel')
        ->with('catin_terakhir')
        ->groupBy('t_penduduk.id')
        ->paginate(20)->withQueryString();

        return Inertia::render('Penduduk/index', [
            'njns' => 'Calon Pengantin',
            'croute' => 'catin',
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
            'njns' => 'Calon Pengantin',
            'croute' => 'catin',
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
    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());

        $id_penduduk = $request->id_penduduk;
        $tgl_pengukuran = $request->tgl_pengukuran;

        $validated = $request->validate([
            'id_penduduk' => ['required','string', 'max:36'],
            'puskesmas.id' => ['required','integer'],
            'puskesmas.nama' => ['required','string', 'max:100'],
            'posyandu' => ['required','string', 'max:100'],
            'tgl_pengukuran' => [
                'date', 
                Rule::unique(MasterCatin::class)->where(function ($query) use($id_penduduk,$tgl_pengukuran) {
                    return $query->where('id_penduduk', $id_penduduk)->where('tgl_pengukuran', $tgl_pengukuran);
                }),
            ],
            'berat' => ['string', 'max:15'],
            'tinggi' => ['string', 'max:15'],
        ]);

        $createData = $request->all();
        $createData['id_puskesmas'] = $request->puskesmas['id'];
        $createData['puskesmas'] = $request->puskesmas['nama'];
        //$createData['usia_saat_ukur'] = Carbon::parse($request->tgl_lahir)->diff(Carbon::parse($request->tgl_pengukuran))->format('%y Tahun - %m Bulan - %d Hari');

        //dd($createData);
        //hitung pengukuran
        //$createData = $this->hitungBBTBU($createData);

        $crData = MasterCatin::create($createData);

        //$this->updateLastPengukuran($createData['nik']);
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
     * @param  \App\Models\MasterCatin  $masterCatin
     * @return \Illuminate\Http\Response
     */
    public function show(MasterCatin $masterCatin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterCatin  $masterCatin
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterCatin $masterCatin, $id)
    {
        $nid = Crypt::decryptString($id);
        
        return Inertia::render('Penduduk/edit', [
            'njns' => 'Calon Pengantin',
            'croute' => 'catin',
            'data' => MasterPenduduk::with(['catin'])->find($nid),
            'masterPuskesmas' => MasterPuskesmas::all(),
            'masterKecamatan' => MasterKecamatan::all()->pluck('nama', 'id'),
            'masterKelurahan' => MasterKelurahan::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterCatin  $masterCatin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterCatin $masterCatin, $id) : RedirectResponse
    {
        $id_penduduk = $request->id_penduduk;
        $tgl_pengukuran = $request->tgl_pengukuran;

        $validated = $request->validate([
            'id_penduduk' => ['required','string', 'max:36'],
            'puskesmas.id' => ['required','integer'],
            'puskesmas.nama' => ['required','string', 'max:100'],
            'posyandu' => ['required','string', 'max:100'],
            'tgl_pengukuran' => [
                'date', 
                Rule::unique(MasterCatin::class)->where(function ($query) use($id_penduduk,$tgl_pengukuran) {
                    return $query->where('id_penduduk', $id_penduduk)->where('tgl_pengukuran', $tgl_pengukuran);
                })->ignore($id),
            ],
            'berat' => ['string', 'max:15'],
            'tinggi' => ['string', 'max:15'],
        ]);

        $updateData = $request->all();
        $updateData['id_puskesmas'] = $request->puskesmas['id'];
        $updateData['puskesmas'] = $request->puskesmas['nama'];

        //hitung pengukuran
        //$updateData = $this->hitungBBTBU($updateData);

        $toUpdate = $masterCatin->find($id);
        $toUpdate->update($updateData);

        //update last pengukuran ke t_balita
        //$this->updateLastPengukuran($toUpdate['nik']);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pengukuran Berhasil di Update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterCatin  $masterCatin
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterCatin $masterCatin, $id): RedirectResponse
    {
        $nid = Crypt::decryptString($id);
        $data = $masterCatin->find($nid);
        //$nik = $data->nik;
        $data->delete();

        //$this->updateLastPengukuran($nik);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pengukuran Berhasil di Dihapus'
        ]);
    }
}
