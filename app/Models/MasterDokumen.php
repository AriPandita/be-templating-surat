<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDokumen extends Model
{
    use SoftDeletes;

    protected $table = 'master_dokumen';
    protected $fillable = ['doc_number', 'doc_title', 'doc_desc', 'doc_date', 'doc_rev', 'file_path', 'is_approved'];

    public function detailDokumens()
    {
        return $this->hasMany(DetailMasterDokumen::class, 'doc_id');
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'doc_id');
    }
}
