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
        Schema::table('material', function (Blueprint $table) {
            $table->integer('stock')->default(0);
        });

        Schema::table('manual', function (Blueprint $table) {
            $table->integer('stock')->default(0);
        });

        Schema::table('seguridad', function (Blueprint $table) {
            $table->integer('stock')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_manual_seguridad_tables', function (Blueprint $table) {
            //
        });
    }
};
