<?php

namespace App\Http\Controllers;

use App\Models\MasterPengguna;
use App\Models\MasterRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

use DB;

class MasterPenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : Response
    {
        $data = MasterPengguna::when($request->search, function($query, $search){
            $query->where('users.name', 'LIKE', '%'.$search.'%');
        })->with(['akses'])->paginate(10)->withQueryString();
        return Inertia::render('Pengguna/index', [
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Pengguna/edit', [
            'role' => MasterRole::all()->pluck('nama', 'id'),
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
        $validated = $request->validate([
            'nip' => ['required','string', 'max:20', Rule::unique(MasterPengguna::class)],
            'name' => ['required','string', 'max:255', Rule::unique(MasterPengguna::class)],
            'username' => ['required','string', 'max:20', Rule::unique(MasterPengguna::class)],
            'email' => ['required','email', Rule::unique(MasterPengguna::class)],
            'fileUpload' => ['nullable', 'image'],
        ]);

        $toCreate = $request->all();
        $toCreate['password'] = Hash::make($toCreate['password']);

        $toCreate = $this->uploadImg($request, $toCreate);

        $crData = MasterPengguna::create($toCreate);
        //dd($crData);
        $encid = Crypt::encryptString($crData->id);

        return Redirect::route('pengguna.edit', ['id' => $encid])->with([
            'type'      => 'success',
            'message'   => 'Data Pengguna Berhasil di Tambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterPengguna  $masterPengguna
     * @return \Illuminate\Http\Response
     */
    public function show(MasterPengguna $masterPengguna)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterPengguna  $masterPengguna
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterPengguna $masterPengguna, $id)
    {
        $nid = Crypt::decryptString($id);
        $data = $masterPengguna->find($nid);

        return Inertia::render('Pengguna/edit', [
            'data' => $data,
            'role' => MasterRole::all()->pluck('nama', 'id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterPengguna  $masterPengguna
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterPengguna $masterPengguna, $id) : RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['required','string', 'max:20', Rule::unique(MasterPengguna::class)->ignore($id)],
            'name' => ['required','string', 'max:255', Rule::unique(MasterPengguna::class)->ignore($id)],
            'username' => ['required','string', 'max:20', Rule::unique(MasterPengguna::class)->ignore($id)],
            'email' => ['required','email', Rule::unique(MasterPengguna::class)->ignore($id)],
            'fileUpload' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],

        ]);

        $updateData = $request->only('nip', 'name', 'username','email', 'role', 'status_user');

        $updateData = $this->uploadImg($request, $updateData);
        //$this->dataOrangTua($request);

        MasterPengguna::find($id)->update($updateData);

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pengguna Berhasil di Ubah'
        ]);
    }

    function uploadImg(Request $request, $data){
        if($request->hasFile('fileUpload')){
            $fileName = $request->nip. '_' . time() . '.'. $request->fileUpload->extension();  
            $request->fileUpload->move(public_path('file'), $fileName);  
            $data['fileUpload'] = $fileName;
            //return $fileName;  
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterPengguna  $masterPengguna
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterPengguna $masterPengguna, $id)
    {
        $nid = Crypt::decryptString($id);
        $data = $masterPengguna->find($nid);
        $data->delete();

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Pengguna Berhasil di Dihapus'
        ]);
    }
}
