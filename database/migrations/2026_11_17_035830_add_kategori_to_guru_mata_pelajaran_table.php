<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru_mata_pelajaran', function (Blueprint $table): void {
            $table->enum('kategori', ['Putra', 'Putri'])->default('Putri');
            $table->unique(['guru_id', 'mata_pelajaran_id', 'tingkat', 'kategori'], 'guru_mapel_tingkat_kategori_unique');
        });

        Schema::table('guru_mata_pelajaran', function (Blueprint $table): void {
            $table->dropUnique('guru_mapel_tingkat_unique');
        });
    }

    public function down(): void
    {
        Schema::table('guru_mata_pelajaran', function (Blueprint $table): void {
            $table->unique(['guru_id', 'mata_pelajaran_id', 'tingkat'], 'guru_mapel_tingkat_unique');
        });

        Schema::table('guru_mata_pelajaran', function (Blueprint $table): void {
            $table->dropUnique('guru_mapel_tingkat_kategori_unique');
            $table->dropColumn('kategori');
        });
    }
};
