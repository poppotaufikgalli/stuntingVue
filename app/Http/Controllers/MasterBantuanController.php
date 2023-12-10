<?php

namespace App\Http\Controllers;

use App\Models\MasterBantuan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

use DB;

class MasterBantuanController extends Controller
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
        $validated = $request->validate([
            'nik_balita' => ['required','string', 'max:20'],
            'penduduk.id' => ['nullable','string', 'max:36'],
            'asal_bantuan.id' => ['required','integer'],
            'nama_pemberi' => ['nullable','string', 'max:200'],
            'tgl_bantuan' => ['required','date'],
            'tempat_bantuan' => ['required','string', 'max:100'],
            'item_bantuan' => ['required'],
            'nilai_bantuan' => ['required'],
        ]);

        $createData = $request->all();
        
        $createData['asal_bantuan'] = $createData['asal_bantuan']['id'];
        if($createData['asal_bantuan'] == 1){
            $createData['id_penduduk'] = $createData['penduduk']['id'];
            $createData['nama_pemberi'] = $createData['asal_bantuan'] == 1 ? $createData['penduduk']['nama'] : $createData['nama_pemberi'];
        }

        $crData = MasterBantuan::create($createData);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Distribusi Bantuan Berhasil di Tambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterBantuan  $masterBantuan
     * @return \Illuminate\Http\Response
     */
    public function show(MasterBantuan $masterBantuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterBantuan  $masterBantuan
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterBantuan $masterBantuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterBantuan  $masterBantuan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterBantuan $masterBantuan, $id) : RedirectResponse
    {
        $validated = $request->validate([
            'nik_balita' => ['required','string', 'max:20'],
            'penduduk.id' => ['nullable','string', 'max:36'],
            'asal_bantuan.id' => ['required','integer'],
            'nama_pemberi' => ['nullable','string', 'max:200'],
            'tgl_bantuan' => ['required','date'],
            'tempat_bantuan' => ['required','string', 'max:100'],
            'item_bantuan' => ['required'],
            'nilai_bantuan' => ['required'],
        ]);

        $updateData = $request->all();
        $updateData['asal_bantuan'] = $updateData['asal_bantuan']['id'];
        //dd($updateData);
        if($updateData['asal_bantuan'] == 1){
            $updateData['id_penduduk'] = $updateData['penduduk']['id'];
        }
        
        $updateData['nama_pemberi'] = $updateData['asal_bantuan'] == 1 ? $updateData['penduduk']['nama'] : $updateData['nama_pemberi'];

        $toUpdate = $masterBantuan->find($id);
        $toUpdate->update($updateData);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Distribusi Bantuan Berhasil di Update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterBantuan  $masterBantuan
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterBantuan $masterBantuan, $id)
    {
        $nid = Crypt::decryptString($id);
        $data = $masterBantuan->find($nid);
        //dd($data);
        $data->delete();

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Distribusi Bantuan Berhasil di Dihapus'
        ]);
    }
}
