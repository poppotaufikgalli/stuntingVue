<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use DateTimeInterface;

class MasterPengguna extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users';
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

    public function akses()
    {
        return $this->belongsTo(MasterRole::class, 'role');
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
        'name',
        'username',
        'role',
        'fileUpload',
        'nip',
        'email',
        'status_user',
        'password',
        'refresh_role',
    ];

    protected $dates = ['deleted_at'];

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

        static::deleting(function ($model) {
            $model->deleted_by = is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : 1;
            //$model->deleted_at = Carbon::now();
        });
    }
}
