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

class MasterHamil extends Model
{
    use HasFactory;

    protected $table = 't_hamil';
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
        'hamil_ke',
        'anak_ke',
        'id_puskesmas',
        'puskesmas',
        'posyandu',
        'tgl_pengukuran',
        'usia_kehamilan',
        'berat',
        'tinggi',
        'lila',
        'lila_stunting',
        'tekanan_darah',
        'tinggi_fu',
        'hb',
        'vdrl',
        'denyut_janin',
        'protein_urine',
        'reduksi_urine',
        'imunisasi_tt',
        'tablet_fe',
        'perawatan_payudara',
        'senam_hamil',
        'temu_wicara',
        'terapi_yodium',
        'terapi_malaria',
        'catatan',
        'nama_file_upload',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            //$model->id = Str::uuid()->toString();
            $model->lila_stunting = $model->lila <= 23.5 ? 'Ya' : 'Tidak';
            $model->created_by = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
            $model->updated_by = NULL;
        });

        static::updating(function ($model) {
            $model->lila_stunting = $model->lila <= 23.5 ? 'Ya' : 'Tidak';
            $model->updated_by = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
            $model->updated_at = Carbon::now();
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('hamil_ke')->orderBy('tgl_pengukuran');
        });
    }
}
