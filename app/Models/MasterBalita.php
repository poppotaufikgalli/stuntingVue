<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use DateTimeInterface;

class MasterBalita extends Model
{
    use HasFactory;

    protected $table = 't_balita';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /*public function asuhan()
    {
        return $this->hasOne(Asuh::class, 'nik', 'nik');
    }

    public function nkec()
    {
        return $this->belongsTo(MasterKecamatan::class, 'kec');
    }*/

    public function pengukuran()
    {
        return $this->hasMany(MasterEppgbm::class, 'nik','nik');
    }

    public function pengukuran_terakhir()
    {
        return $this->hasOne(MasterEppgbm::class, 'nik','nik')->latestOfMany();
    }

    public function ortu()
    {
        return $this->hasOne(MasterPenduduk::class, 'id', 'id_ortu');
    }

    public function ortu_asuh()
    {
        return $this->hasMany(MasterPendudukAsuh::class, 'nik_balita', 'nik');
    }

    public function bantuan()
    {
        return $this->hasMany(MasterBantuan::class, 'nik_balita', 'nik');
    }

    public function kecamatan()
    {
        return $this->hasOne(MasterKecamatan::class, 'id', 'kec');
    }

    public function kelurahan()
    {
        return $this->hasOne(MasterKelurahan::class, 'id', 'desa_kel');
    }

    protected $appends = [
        'encid',
        'umur',
    ];

    public function getEncidAttribute()
    {
        $id = $this->id;
        return Crypt::encryptString($id);
    }

    public function getUmurAttribute()
    {
        //return Carbon::parse($this->attributes['tgl_lahir'])->age;
        //$tgl_lahir = $this->tgl_lahir;
        return Carbon::parse($this->tgl_lahir)->diff(Carbon::now())->format('%y th %m bln');
        //return Carbon::parse($this->tgl_lahir)->age;
    }

    protected $fillable = [
        'nik',
        'nama',
        'jk',
        'tgl_lahir',
        'bb_lahir',
        'tb_lahir',
        'id_ortu',
        'nama_ortu',
        'prov',
        'kab_kota',
        'kec',
        'desa_kel',
        'rt',
        'rw',
        'alamat',
        'nama_file_upload',
    ];

    public $timestamps = false;

    /*protected $dates = ['tgl_lahir'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }*/

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
            $builder->orderBy('tgl_lahir');
        });
    }
}
