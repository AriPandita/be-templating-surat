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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('doc_id')->constrained('master_dokumen')->onDelete('cascade'); // FK to master_dokumens
            $table->json('data'); // document data in json format
            $table->string('doc_number'); // document number
            $table->text('title'); // document title
            $table->text('desc'); // document description
            $table->date('doc_date'); // document date of creation
            $table->timestamps(); // Created_at & Updated_at
            $table->softDeletes(); // Soft delete field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
