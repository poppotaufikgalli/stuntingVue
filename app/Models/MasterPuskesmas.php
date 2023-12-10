<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use DateTimeInterface;

class MasterPuskesmas extends Model
{
    use HasFactory;

    protected $table = 't_puskesmas';
    public $keyType = "string";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id',
        'nama',
        'id_kecamatan',
        'id_desa_kel',
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
            $builder->orderBy('id_kecamatan')->orderBy('id_desa_kel');
        });
    }
}
