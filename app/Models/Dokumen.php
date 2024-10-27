<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokumen extends Model
{
    use SoftDeletes;

    protected $table = 'dokumen';
    protected $fillable = ['doc_id', 'data', 'doc_number', 'title', 'desc', 'doc_date'];

    public function masterDokumen()
    {
        return $this->belongsTo(MasterDokumen::class, 'doc_id');
    }
}
