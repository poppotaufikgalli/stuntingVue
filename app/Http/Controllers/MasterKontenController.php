<?php

namespace App\Http\Controllers;

use App\Models\MasterKonten;
use App\Models\MasterBalita;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

class MasterKontenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Konten/index',[
            'data' => MasterKonten::paginate(10)->withQueryString(),
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterKonten  $masterKonten
     * @return \Illuminate\Http\Response
     */
    public function show(MasterKonten $masterKonten)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterKonten  $masterKonten
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterKonten $masterKonten)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterKonten  $masterKonten
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterKonten $masterKonten)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterKonten  $masterKonten
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterKonten $masterKonten)
    {
        //
    }
}
