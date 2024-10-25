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
        Schema::create('master_karyawan', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Karyawan name
            $table->string('employee_number')->unique(); // Karyawan number
            $table->timestamps(); // Created_at & Updated_at
            $table->softDeletes(); // Soft delete field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_karyawan');
    }
};
