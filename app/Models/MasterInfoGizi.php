<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterInfoGizi extends Model
{
    use HasFactory;

    protected $table = 't_info_gizi';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'nama',
        'caption',
        'jns',
        'zmin',
        'min',
        'zmax',
        'max',
    ];
}
