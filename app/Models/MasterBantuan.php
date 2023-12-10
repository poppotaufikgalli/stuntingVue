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

class MasterBantuan extends Model
{
    use HasFactory;

    protected $table = 't_bantuan';
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

    public function balita()
    {
        return $this->belongsTo(Masterbalita::class, 'nik_balita');
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
        'id',
        'nik_balita',
        'id_penduduk',
        'asal_bantuan',
        'nama_pemberi',
        'tgl_bantuan',
        'tempat_bantuan',
        'item_bantuan',
        'nilai_bantuan',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid()->toString();
            $model->created_by = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
            $model->updated_by = NULL;
        });

        static::updating(function ($model) {
            $model->updated_by = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
            $model->updated_at = Carbon::now();
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('tgl_bantuan');
        });
    }
}
