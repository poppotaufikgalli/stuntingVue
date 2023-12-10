<?php

namespace App\Imports;

use App\Models\MasterEppgbm;
use App\Models\MasterPuskesmas;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class EppgbmImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
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
        if (!isset($row['nik'])) {
            return null;
        }

        //dd($row);

        $puskesmas = MasterPuskesmas::all()->pluck('id', 'nama');
        
        $nik = preg_replace('/[^0-9]/s','',$row['nik']);
        $tgl_lahir = preg_replace('/[^0-9-]/s','',$row['tgl_lahir']);
        $tgl_pengukuran = preg_replace('/[^0-9-]/s','',$row['tanggal_pengukuran']);

        $usia_saat_ukur = Carbon::parse($tgl_lahir)->diff($tgl_pengukuran)->format('%y Tahun - %m Bulan - %d Hari');



        $eppgbmData = [
            'nik'               => $nik,
            'id_puskesmas'      => $puskesmas[$row['puskesmas']] ?? null,
            'puskesmas'         => $row['puskesmas'],
            'posyandu'          => $row['posyandu'],
            'usia_saat_ukur'    => $usia_saat_ukur,
            'tgl_pengukuran'    => $tgl_pengukuran,
            'berat'             => $row['berat'],
            'tinggi'            => $row['tinggi'],
            'lila'              => $row['lila'],
            //'cara_ukur_tinggi'     => $row[10],
            'bb_u'              => $row['bbu'],
            'zz_bb_u'           => $row['zs_bbu'],
            'tb_u'              => $row['tbu'],
            'zz_tb_u'           => $row['zs_tbu'],
            'bb_tb'             => $row['bbtb'],
            'zz_bb_tb'          => $row['zs_bbtb'],
            'naik_berat_badan'  => $row['naik_berat_badan'],
            'pmt_diterima_kg'   => $row['pmt_diterima_kg'],
            'jml_vit_a'         => $row['jml_vit_a'],
            'kpsp'              => $row['kpsp'],
            'kia'               => $row['kia'],
            'nama_file_upload'  => $this->filename,
        ];

        return new MasterEppgbm($eppgbmData);
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function rules(): array
    {
        return [
            'nik' => 'unique:t_eppgbm,nik,tgl_pengukuran',
        ];
    }
}
