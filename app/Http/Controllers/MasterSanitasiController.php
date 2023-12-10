<?php

namespace App\Http\Controllers;

use App\Models\MasterSanitasi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

use DB;

class MasterSanitasiController extends Controller
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
        //
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
            'id_penduduk' => ['required','max:36', Rule::unique(MasterSanitasi::class)],
            'jamban_keluarga' => ['required','string', 'max:100'],
            'status_jamban' => ['required','string', 'max:100'],
            'kebutuhan_bab' => ['required','string', 'max:100'],
            'tempat_sampah' => ['required','string', 'max:100'],
            'penanganan_sampah' => ['required','string', 'max:100'],
            'air_limbah' => ['required','string', 'max:100'],
            'jarak_air_bersih' => ['required','string', 'max:100'],
        ]);

        MasterSanitasi::create($request->all());

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Sanitasi Berhasil di Tambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterSanitasi  $masterSanitasi
     * @return \Illuminate\Http\Response
     */
    public function show(MasterSanitasi $masterSanitasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterSanitasi  $masterSanitasi
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterSanitasi $masterSanitasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterSanitasi  $masterSanitasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterSanitasi $masterSanitasi, $id) : RedirectResponse
    {
        $validated = $request->validate([
            'jamban_keluarga' => ['required','string', 'max:100'],
            'status_jamban' => ['required','string', 'max:100'],
            'kebutuhan_bab' => ['required','string', 'max:100'],
            'tempat_sampah' => ['required','string', 'max:100'],
            'penanganan_sampah' => ['required','string', 'max:100'],
            'air_limbah' => ['required','string', 'max:100'],
            'jarak_air_bersih' => ['required','string', 'max:100'],
        ]);

        $masterSanitasi->where('id_penduduk','=',$id)->update($request->all());

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Sanitasi Berhasil di Diubah'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterSanitasi  $masterSanitasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterSanitasi $masterSanitasi)
    {
        //
    }
}
