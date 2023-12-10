<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\MasterPenduduk;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function getJnsPenduduk($jns="penduduk")
    {
        $result = [
            'njns'   => 'Penduduk / Orang Tua',
            'croute' => 'penduduk',
        ];

        if($jns == 'asuh'){
            $result = [
                'njns'   => 'Orang Tua Asuh',
                'croute' => 'asuh',
            ];

        }elseif($jns == 'remaja'){
            $result = [
                'njns'   => 'Remaja Putri',
                'croute' => 'remaja',
            ];
        }

        return $result;
    }

    function searchDataPenduduk($request, $jns="penduduk"){
        $result = MasterPenduduk::where(function($query) use ($jns){
            if($jns == 'asuh'){
                $query->where('t_penduduk.jns', '=', 'asuh');
            }

            if($jns == 'remaja'){
                $query->where('t_penduduk.jns', '=', 'remaja');
            }
        })
        ->when($request->search, function($query, $search){
            $query->where('t_penduduk.nama', 'LIKE', '%'.$search.'%')->orWhere('t_penduduk.nik', 'LIKE', '%'.$search.'%');
        })
        ->leftjoin('t_kecamatan', 't_kecamatan.id', 'kec')
        ->leftjoin('t_kelurahan', function($join){
            $join->on('t_kelurahan.id', '=', 'desa_kel');
            $join->on('t_kelurahan.id_kecamatan', '=', 'kec');
        })->select('t_penduduk.id as id', 't_penduduk.nik', 't_penduduk.nama', 't_penduduk.pekerjaan', 't_penduduk.instansi', 't_penduduk.alamat', 't_penduduk.rt', 't_penduduk.rw', 't_penduduk.kec', 't_kecamatan.nama as nama_kec', 't_penduduk.desa_kel', 't_kelurahan.nama as nama_desa_kel')
        
        ->with(['balita', 'asuh'])
        ->paginate(20)->withQueryString();
        //->toSql();

        //dd($result);

        return $result;
    }
}
