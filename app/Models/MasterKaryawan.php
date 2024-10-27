<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterKaryawan extends Model
{
    use SoftDeletes;

    protected $table = 'master_karyawan';
    protected $fillable = ['name', 'employee_number'];

    public function logAktivitas()
    {
        return $this->hasMany(LogActivity::class, 'karyawan_id');
    }
}
