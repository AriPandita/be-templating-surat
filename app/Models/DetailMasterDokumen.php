<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailMasterDokumen extends Model
{
    use SoftDeletes;

    protected $table = 'detail_master_dokumen';
    protected $fillable = ['doc_id', 'placeholder', 'type', 'desc'];

    public function masterDokumen()
    {
        return $this->belongsTo(MasterDokumen::class, 'doc_id');
    }
}
