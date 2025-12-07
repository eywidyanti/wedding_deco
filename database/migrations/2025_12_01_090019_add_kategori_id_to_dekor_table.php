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
        Schema::table('dekor', function (Blueprint $table) {
            $table->string('tema')->nullable();
            $table->string('gaya')->nullable();
            $table->string('warna')->nullable();
            $table->foreignId('kategori_id')
                  ->after('user_id')
                  ->nullable()
                  ->constrained('kategori_dekor')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dekor', function (Blueprint $table) {
            //
        });
    }
};
