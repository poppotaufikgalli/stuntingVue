<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterRemaja;
use App\Models\MasterPenduduk;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;
use App\Models\MasterBalita;
use App\Models\MasterPuskesmas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class MasterRemajaController extends Controller
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
        ->join('t_remaja', 't_remaja.id_penduduk', 't_penduduk.id')
        ->leftjoin('t_kecamatan', 't_kecamatan.id', 't_penduduk.kec')
        ->leftjoin('t_kelurahan', function($join){
            $join->on('t_kelurahan.id', '=', 't_penduduk.desa_kel');
            $join->on('t_kelurahan.id_kecamatan', '=', 't_penduduk.kec');
        })->select('t_penduduk.id as id', 't_penduduk.nik', 't_penduduk.nama', 't_penduduk.pekerjaan', 't_penduduk.instansi', 't_penduduk.alamat', 't_penduduk.rt', 't_penduduk.rw', 't_penduduk.kec', 't_kecamatan.nama as nama_kec', 't_penduduk.desa_kel', 't_kelurahan.nama as nama_desa_kel')
        ->groupBy('t_penduduk.id')
        ->with('remaja_terakhir')
        ->paginate(20)->withQueryString();

        return Inertia::render('Penduduk/index', [
            'njns' => 'Remaja Putri',
            'croute' => 'remaja',
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
            'njns' => 'Remaja Putri',
            'croute' => 'remaja',
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

        $id_penduduk = $request->id_penduduk;
        $tgl_pengukuran = $request->tgl_pengukuran;

        $validated = $request->validate([
            'id_penduduk' => ['required','string', 'max:36'],
            'puskesmas.id' => ['required','integer'],
            'puskesmas.nama' => ['required','string', 'max:100'],
            'posyandu' => ['required','string', 'max:100'],
            'tgl_pengukuran' => [
                'date', 
                Rule::unique(MasterRemaja::class)->where(function ($query) use($id_penduduk,$tgl_pengukuran) {
                    return $query->where('id_penduduk', $id_penduduk)->where('tgl_pengukuran', $tgl_pengukuran);
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
            'tinggi' => ['string', 'max:15'],
        ]);

        $createData = $request->all();
        $createData['id_puskesmas'] = $request->puskesmas['id'];
        $createData['puskesmas'] = $request->puskesmas['nama'];
        $createData['usia_saat_ukur'] = Carbon::parse($request->tgl_lahir)->diff(Carbon::parse($request->tgl_pengukuran))->format('%y Tahun - %m Bulan - %d Hari');

        //dd($createData);
        //hitung pengukuran
        //$createData = $this->hitungBBTBU($createData);

        $crData = MasterRemaja::create($createData);

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
    public function edit($id) : Response
    {
        $nid = Crypt::decryptString($id);
        
        return Inertia::render('Penduduk/edit', [
            'njns' => 'Remaja Putri',
            'croute' => 'remaja',
            'data' => MasterPenduduk::with(['remaja'])->find($nid),
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterRemaja $masterRemaja, $id) : RedirectResponse
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
                Rule::unique(MasterRemaja::class)->where(function ($query) use($id_penduduk,$tgl_pengukuran) {
                    return $query->where('id_penduduk', $id_penduduk)->where('tgl_pengukuran', $tgl_pengukuran);
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
        ]);

        $updateData = $request->all();
        $updateData['id_puskesmas'] = $request->puskesmas['id'];
        $updateData['puskesmas'] = $request->puskesmas['nama'];

        //hitung pengukuran
        //$updateData = $this->hitungBBTBU($updateData);

        $toUpdate = $masterRemaja->find($id);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterRemaja $masterRemaja, $id) : RedirectResponse
    {
        $nid = Crypt::decryptString($id);
        $data = $masterRemaja->find($nid);
        //$nik = $data->nik;
        $data->delete();

        //$this->updateLastPengukuran($nik);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pengukuran Berhasil di Dihapus'
        ]);
    }
}
