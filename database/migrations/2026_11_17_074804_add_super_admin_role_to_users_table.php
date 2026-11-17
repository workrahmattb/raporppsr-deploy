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
            $table->enum('role', ['super_admin', 'admin', 'guru', 'wali_kelas'])->default('guru')->change();
        });

        DB::table('users')
            ->where('username', 'admin')
            ->where('role', 'admin')
            ->update(['role' => 'super_admin']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);

        Schema::table('users', function (Blueprint $table): void {
            $table->enum('role', ['admin', 'guru', 'wali_kelas'])->default('guru')->change();
        });
    }
};
