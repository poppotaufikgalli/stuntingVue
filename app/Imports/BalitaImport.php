<?php

namespace App\Imports;

use App\Models\MasterBalita;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class BalitaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    protected $filename;

    public function __construct($filename="") {
        $this->filename = $filename;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row);

        if (!isset($row['nik'])) {
            return null;
        }

        $desakel = implode(" ", array_filter(explode(" ", $row['desakel'])));

        $kelurahan = MasterKelurahan::where('nama', '=', $desakel)->orWhere('alias', '=', $desakel)->first();
        $kecamatan = MasterKecamatan::where('nama', '=', $row['kec'])->first();

        if($kelurahan){
            $kecamatan = MasterKecamatan::where('id', '=', $kelurahan->id_kecamatan)->orWhere('nama', '=', $row['kec'])->first();    
        }
        

        $nik = preg_replace('/[^0-9]/s','',$row['nik']);
        $jk = preg_replace('/[^A-Z]/s','',$row['jk']);
        $tgl_lahir = preg_replace('/[^0-9-]/s','',$row['tgl_lahir']);

        $balitaData = [
            'nik'       => $nik,
            'nama'      => $row['nama'],
            'jk'        => $jk,
            'tgl_lahir' => $tgl_lahir,
            'bb_lahir'  => $row['bb_lahir'],
            'tb_lahir'  => $row['tb_lahir'],
            'nama_ortu' => $row['nama_ortu'],
            'prov'      => $row['prov'],
            'kab_kota'  => $row['kabkota'],
            'kec'       => $kecamatan ? $kecamatan->id : $row['kec'],
            'desa_kel'  => $kelurahan ? $kelurahan->id : $row['desakel'],
            'rt'        => $row['rt'],
            'rw'        => $row['rw'],
            'alamat'    => $row['alamat'],
            'nama_file_upload'  => $this->filename,
        ];

        return new MasterBalita($balitaData);
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function rules(): array
    {
        return [
            'nik' => 'unique:t_balita,nik,tgl_lahir',
        ];
    }
}
