<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->enum('kategori_admin', ['Putra', 'Putri'])->nullable();
        });
        Schema::table('siswas_rapor', function (Blueprint $table): void {
            $table->enum('kategori', ['Putra', 'Putri'])->default('Putri');
        });
        DB::table('siswas_rapor')->whereIn('id', DB::table('kelas_siswa')
            ->join('kelas_rapor', 'kelas_siswa.kelas_id', '=', 'kelas_rapor.id')
            ->where('kelas_rapor.kategori', 'Putra')
            ->select('kelas_siswa.siswa_id'))
            ->update(['kategori' => 'Putra']);
        DB::table('users')->where('role', 'admin')->update(['kategori_admin' => 'Putri']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('kategori_admin');
        });
        Schema::table('siswas_rapor', function (Blueprint $table): void {
            $table->dropColumn('kategori');
        });
    }
};
