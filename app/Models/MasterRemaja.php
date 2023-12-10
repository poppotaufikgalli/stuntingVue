<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use DateTimeInterface;

class MasterRemaja extends Model
{
    use HasFactory;

    protected $table = 't_remaja';
    public $keyType = "string";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public function penduduk()
    {
        return $this->belongsTo(MasterPenduduk::class, 'id_penduduk');
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
        'id_penduduk',
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
        'lila',
        'hb',
        'ttd',
        'nama_file_upload',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            //$model->id = Str::uuid()->toString();
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
