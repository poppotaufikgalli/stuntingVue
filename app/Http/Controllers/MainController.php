<?php

namespace App\Http\Controllers;

use App\Models\MasterBalita;
use App\Models\MasterEppgbm;
use App\Models\MasterPenduduk;
use App\Models\MasterKelurahan;
use App\Models\MasterKecamatan;
use App\Models\MasterJenisBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Inertia\Inertia;
use Inertia\Response;

use DB;

class MainController extends Controller
{
    public function index(): Response
    {
        $nbalitakec = $this->getNbalitakec();
        $nbalitakeckel = $this->getNbalitakeckel();
        $petakelurahantpi = $this->getPetakelurahantpi($nbalitakeckel);
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'nbalitakec' => $nbalitakec,
            'nbalitakeckel' => $nbalitakeckel,
            'petakelurahantpi' => $petakelurahantpi,
        ]);
    }

    function getNbalitakec(){
        $nbalitakec = DB::table('t_balita')
            ->join('t_eppgbm', 't_eppgbm.nik', 't_balita.nik')
            ->join('t_kecamatan', 't_kecamatan.id', 't_balita.kec')
            ->select(
                't_balita.kec',
                DB::Raw('t_kecamatan.nama as nama_kec'),
                DB::Raw('count(distinct(t_balita.nik)) as jml'),
                DB::Raw('sum(case t_eppgbm.tb_u when "Pendek" then 1 else 0 end) as Pendek'),
                DB::Raw('sum(case t_eppgbm.tb_u when "Sangat Pendek" then 1 else 0 end) as "Sangat_Pendek"'),
            )->where('t_eppgbm.tb_u', '=', 'Pendek')->orWhere('t_eppgbm.tb_u', '=', 'Sangat_Pendek')->groupBy('t_balita.kec')->orderBy('t_balita.kec', 'asc')->get();

        return $nbalitakec;
    }

    function getNbalitakeckel(){
        $nbalitakeckel = DB::table('t_eppgbm')
            ->join('t_balita', 't_balita.nik', 't_eppgbm.nik')
            ->join('t_kecamatan', 't_kecamatan.id', 't_balita.kec')
            ->join('t_kelurahan', function($query){
                $query->on('t_kelurahan.id_kecamatan', 't_balita.kec')->on('t_kelurahan.id', 't_balita.desa_kel');
            })
            ->select(
                DB::Raw('concat(t_balita.kec, t_balita.desa_kel) as kd_wil'),
                't_balita.kec',
                DB::Raw('t_kecamatan.nama as nama_kec'),
                't_balita.desa_kel',
                DB::Raw('t_kelurahan.nama as nama_desa_kel'),
                DB::Raw('count(t_balita.nik) as jml'),
                DB::Raw('sum(case t_eppgbm.tb_u when "Pendek" then 1 else 0 end) as Pendek'),
                DB::Raw('sum(case t_eppgbm.tb_u when "Sangat Pendek" then 1 else 0 end) as "Sangat_Pendek"'),
                DB::Raw('sum(case when (t_balita.jk = "L" and t_eppgbm.tb_u = "Pendek" ) then 1 else 0 end) as L_P'),
                DB::Raw('sum(case when (t_balita.jk = "L" and t_eppgbm.tb_u = "Sangat Pendek" ) then 1 else 0 end) as L_SP'),
                DB::Raw('sum(case when (t_balita.jk = "P" and t_eppgbm.tb_u = "Pendek" ) then 1 else 0 end) as P_P'),
                DB::Raw('sum(case when (t_balita.jk = "P" and t_eppgbm.tb_u = "Sangat Pendek" ) then 1 else 0 end) as P_SP'),
            )->groupBy('t_balita.kec', 't_balita.desa_kel')->orderBy('t_balita.kec', 'asc')->get()->keyBy('kd_wil')->toArray();
        return $nbalitakeckel;
    }

    function getPetakelurahantpi($nbalitakeckel){
        //$nbalitakeckel = $this->nbalitakeckel();

        $petakelurahantpi = json_decode(file_get_contents(public_path() . "/geo/petakelurahantpi.geojson"), true);

        foreach ($petakelurahantpi['features'] as $key => $value) {
            $kd_wil = $value['properties']['kd_wil'];
            $petakelurahantpi['features'][$key]['properties']['sebaran'] = $nbalitakeckel[$kd_wil]->jml ?? 0; 
            $petakelurahantpi['features'][$key]['properties']['pendek'] = $nbalitakeckel[$kd_wil]->Pendek ?? 0; 
            $petakelurahantpi['features'][$key]['properties']['sangat_pendek'] = $nbalitakeckel[$kd_wil]->Sangat_Pendek ?? 0; 
        }

        return $petakelurahantpi;
    }

    public function dashboard() : Response
    {
        $dtBalita = MasterBalita::with(['pengukuran_terakhir'])
        ->join('t_kelurahan', function($join){
            $join->on(['t_kelurahan.id' => 'desa_kel', 't_kelurahan.id_kecamatan' => 'kec']);
        })
        ->join('t_kecamatan', function($join){
            $join->on(['t_kecamatan.id' => 'kec']);
        })
        ->select('nik', 'jk', 'kec','t_kecamatan.nama as nama_kec', 'desa_kel', 't_kelurahan.nama as nama_desa_kel')
        ->orderBy('kec')->orderBy('desa_kel')
        ->get();

        $dtBantuan = MasterJenisBantuan::with('bantuan')->get();
        $dtRemaja = MasterPenduduk::with('remaja_terakhir')->has('remaja')->get();
        $dtCatin = MasterPenduduk::with('catin_terakhir')->has('catin')->get();
        $dtHamil = MasterPenduduk::with('hamil_terakhir')->has('hamil')->get();

        return Inertia::render('Dashboard', [
            'dtBalita' => $dtBalita,
            'dtRemaja' => $dtRemaja,
            'dtCatin' => $dtCatin,
            'dtHamil' => $dtHamil,
            'dtBantuan' => $dtBantuan,
            'dtKecamatan' => MasterKecamatan::with('kelurahan')->where('id', '<>', '000')->get(),
            'dtKelurahan' => MasterKelurahan::with('kecamatan')->where('id_kecamatan', '<>', '000')->orderBy('id_kecamatan')->get(),
            'petakelurahantpi' => json_decode(file_get_contents(public_path() . "/geo/petakelurahantpi.geojson"), true),
        ]);
    }
}
