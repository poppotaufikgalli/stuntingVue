<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use DateTimeInterface;

class MasterPenduduk extends Model
{
    use HasFactory;

    protected $table = 't_penduduk';
    public $keyType = "string";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $appends = [
        'encid'
    ];

    public function getEncidAttribute()
    {
        $id = $this->id;
        return Crypt::encryptString($id);
    }

    public function balita()
    {
        return $this->hasMany(MasterBalita::class, 'id_ortu');
    }

    public function asuh()
    {
        return $this->hasMany(MasterPendudukAsuh::class, 'id_penduduk');
    }

    public function remaja()
    {
        return $this->hasMany(MasterRemaja::class, 'id_penduduk');
    }

    public function remaja_terakhir()
    {
        return $this->hasOne(MasterRemaja::class, 'id_penduduk')->latest();
    }

    public function hamil()
    {
        return $this->hasMany(MasterHamil::class, 'id_penduduk');
    }

    public function hamil_terakhir()
    {
        return $this->hasOne(MasterHamil::class, 'id_penduduk')->latest();
    }

    public function catin()
    {
        return $this->hasMany(MasterCatin::class, 'id_penduduk');
    }

    public function catin_terakhir()
    {
        return $this->hasOne(MasterCatin::class, 'id_penduduk')->latest();
    }

    public function sanitasi()
    {
        return $this->hasOne(MasterSanitasi::class, 'id_penduduk');
    }

    protected $fillable = [
        'id',
        'nik',
        'nama',
        'tgl_lahir',
        'jns',
        'pekerjaan',
        'instansi',
        'prov',
        'kab_kota',
        'kec',
        'desa_kel',
        'rt',
        'rw',
        'alamat',
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
            $builder->orderBy('nama');
        });
    }
}
