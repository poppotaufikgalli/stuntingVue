<?php

namespace App\Http\Controllers;

use App\Models\MasterRole;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

use DB;

class MasterRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : Response
    {
        $data = MasterRole::when($request->search, function($query, $search){
            $query->where('t_roke.nama', 'LIKE', '%'.$search.'%')->orWhere('t_role.akses', 'LIKE', '%'.$search.'%');
        })->with(['users'])->paginate(20)->withQueryString();

        //dd($listBalita);
        
        return Inertia::render('Role/index', [
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
        $routes = collect(Route::getRoutes())->filter(function($route) {
            $prefix = $route->getPrefix();
            $name = $route->getName();
            return ($prefix != "") && (!in_array($prefix, ['sanctum', '_ignition', 'api']));
        })->groupBy(['action.prefix', 'action.as']);

        //dd($routes);
        return Inertia::render('Role/edit', [
            'routeList' => $routes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());

        $validated = $request->validate([
            'nama' => ['required','string', 'max:25', Rule::unique(MasterRole::class)],
            'ket' => ['required','string', 'max:100'],
        ]);

        $crData = $request->only('nama', 'ket');

        if($request->has('akses')){
            $crData['akses'] = implode(', ', $request->akses);
        }

        //dd($crData);

        $createData = MasterRole::create($crData);
        $encid = $createData->encid;

        return Redirect::route('akses.edit', ['id' => $encid])->with([
            'type'      => 'success',
            'message'   => 'Data Hak Akses Berhasil di Tambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterRole  $masterRole
     * @return \Illuminate\Http\Response
     */
    public function show(MasterRole $masterRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterRole  $masterRole
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterRole $masterRole, $id) : Response
    {
        $nid = Crypt::decryptString($id);
        $routes = collect(Route::getRoutes())->filter(function($route) {
            $prefix = $route->getPrefix();
            $name = $route->getName();
            return ($prefix != "") && (!in_array($prefix, ['sanctum', '_ignition', 'api']));
        })->groupBy(['action.prefix', 'action.as']);

        return Inertia::render('Role/edit', [
            'routeList' => $routes,
            'data' => $masterRole->find($nid),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterRole  $masterRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterRole $masterRole, $id) : RedirectResponse
    {
        //dd($request->all());

        $validated = $request->validate([
            'nama' => ['required','string', 'max:25', Rule::unique(MasterRole::class)->ignore($id)],
            'ket' => ['required','string', 'max:100'],
        ]);

        $toUpdate = $request->only('nama', 'ket');

        if($request->has('akses')){
            $toUpdate['akses'] = implode(', ', $request->akses);
        }

        $updateData = $masterRole->find($id)->update($toUpdate);
        //$encid = $createData->encid;

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Hak Akses Berhasil di Ubah'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterRole  $masterRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterRole $masterRole, $id) : RedirectResponse
    {
        $nid = Crypt::decryptString($id);
        $data = $masterRole->find($nid);
        $data->delete();

        return Redirect::back()->with([
            'type'      => 'success',
            'message'   => 'Data Group Hak Akses Berhasil di Dihapus'
        ]);
    }
}
