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
        Schema::create('detail_master_dokumen', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('doc_id')->constrained('master_dokumen')->onDelete('cascade'); // FK to master_dokumens
            $table->string('placeholder'); // Placeholder for dokumen
            $table->enum('type', ['string', 'number', 'image']); // Placeholder type
            $table->text('desc')->nullable(); // placeholder description
            $table->timestamps(); // Created_at & Updated_at
            $table->softDeletes(); // Soft delete field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_master_dokumen');
    }
};
