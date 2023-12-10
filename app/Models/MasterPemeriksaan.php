<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use DateTimeInterface;

class MasterPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 't_pemeriksaan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public function balitas()
    {
        return $this->hasOne(MasterBalita::class, 'nik', 'nik');
    }

    public function getUsiaSaatPengukuran($tgl_lahir)
    {
        $tgl_pengukuran = $this->attributes['tgl_pengukuran'];
        return Carbon::parse($tgl_lahir)->diff(Carbon::parse($tgl_pengukuran))->format('%y Tahun - %m Bulan - %d Hari');
    }

    protected $appends = [
        'encid'
    ];

    public function getEncidAttribute()
    {
        $id = $this->id;
        return Crypt::encryptString($id);
    }

    protected $fillable = [
        'nik',
        'id_puskesmas',
        'puskesmas',
        'posyandu',
        'usia_saat_ukur',
        'tgl_pengukuran',
        'berat',
        'tinggi',
        'cara_ukur_tinggi',
        'lila',
        'bb_u',
        'zz_bb_u',
        'tb_u',
        'zz_tb_u',
        'bb_tb',
        'zz_bb_tb',
        'naik_berat_badan',
        'pmt_diterima_kg',
        'jml_vit_a',
        'kpsp',
        'kia',
        'nama_file_upload',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
            $model->updated_by = NULL;
        });

        static::updating(function ($model) {
            $model->updated_by = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
            $model->updated_at = Carbon::now();
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('tgl_pengukuran');
        });
    }
}
