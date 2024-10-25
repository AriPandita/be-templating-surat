<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number')->unique(); // Dokumen number
            $table->text('doc_title'); // Dokumen title
            $table->text('doc_desc'); // Dokumen description
            $table->date('doc_date'); // Date of dokumen
            $table->string('doc_rev'); // Dokumen revision
            $table->timestamps(); // Created_at & Updated_at
            $table->softDeletes(); // Soft delete field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_dokumen');
    }
};
