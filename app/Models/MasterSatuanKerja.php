<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterSatuanKerja extends Model
{
    use SoftDeletes;

    protected $table = 'master_satuan_kerja';
    protected $fillable = ['name'];
}
