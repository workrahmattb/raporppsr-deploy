<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapor_settings', function (Blueprint $table): void {
            $table->string('tanggal_rapor_putra')->nullable();
            $table->string('kepala_sekolah_mts_putra')->nullable();
            $table->string('kepala_sekolah_ma_putra')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rapor_settings', function (Blueprint $table): void {
            $table->dropColumn(['tanggal_rapor_putra', 'kepala_sekolah_mts_putra', 'kepala_sekolah_ma_putra']);
        });
    }
};
