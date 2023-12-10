<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTbU extends Model
{
    use HasFactory;

    protected $table = 't_tb_u';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'umur',
        'jk',
        'min3',
        'min2',
        'min1',
        'median',
        'plus1',
        'plus2',
        'plus3',
    ];
}
