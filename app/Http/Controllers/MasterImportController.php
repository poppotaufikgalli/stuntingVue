<?php

namespace App\Http\Controllers;

use App\Models\MasterImport;
use App\Models\MasterEppgbm;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

use App\Imports\EppgbmImport;
use App\Imports\BalitaImport;
use App\Imports\TestImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MasterImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : Response
    {
        return Inertia::render('Import/index', [
            'data' => MasterImport::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Import/edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : RedirectResponse
    {
        if($request->hasFile('fileUpload')){
            $fileName = 'import_' . time() . '.'. $request->fileUpload->extension();  
            $request->fileUpload->move(public_path('file-import'), $fileName);  

            //$collection = Excel::toCollection(new TestImport, $request->file('fileUpload'));
            //(new TestImport($fileName))->toCollection(public_path('file-import/'.$fileName), null, \Maatwebsite\Excel\Excel::XLS);
            //dd($collection[0]);

            //eppgbm Import
            //(new EppgbmImport($fileName))->import(public_path('file-import/'.$fileName), null, \Maatwebsite\Excel\Excel::XLS);
            Excel::import(new EppgbmImport($fileName), public_path('file-import/'.$fileName));

            //balita Import
            //(new BalitaImport($fileName))->import(public_path('file-import/'.$fileName), null, \Maatwebsite\Excel\Excel::XLS);
            Excel::import(new BalitaImport($fileName), public_path('file-import/'.$fileName));

            $crData = MasterImport::create([
                'filename' => $fileName,
            ]);

            $encid = Crypt::encryptString($crData->id);
            
            return Redirect::route('import.edit', ['id' => $encid])->with([
                'type'      => 'success',
                'message'   => 'Upload File Berhasil'
            ]);
        }else{
            return Redirect::back()->with([
                'type'      => 'error',
                'message'   => 'Upload File gagal, silahkan ulangi proses'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterImport  $masterImport
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //dd("A");
        return response()->download(public_path('file-import/contoh/contoh_file.xlsx'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterImport  $masterImport
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterImport $masterImport, Request $request, $id) : Response
    {
        $nid = Crypt::decryptString($id);
        $fileName = $masterImport->find($nid)->filename;

        $data = MasterEppgbm::where('nama_file_upload', '=', $fileName)->paginate(10);


        return Inertia::render('Import/edit', [
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterImport  $masterImport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterImport $masterImport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterImport  $masterImport
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterImport $masterImport, $id) : RedirectResponse
    {
        $nid = Crypt::decryptString($id);
        $data = $masterImport->find($nid);
        $filename = $data->filename;

        @unlink(public_path('file-import/'.$filename));

        $data->delete();

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'File Import Berhasil di Dihapus'
        ]);
    }
}
