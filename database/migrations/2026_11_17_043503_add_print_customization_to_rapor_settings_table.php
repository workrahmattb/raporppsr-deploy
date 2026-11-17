<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapor_settings', function (Blueprint $table): void {
            $table->json('tampilan_putra')->nullable();
            $table->json('tampilan_putri')->nullable();
            $table->string('logo_putra')->nullable();
            $table->string('logo_putri')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rapor_settings', function (Blueprint $table): void {
            $table->dropColumn(['tampilan_putra', 'tampilan_putri', 'logo_putra', 'logo_putri']);
        });
    }
};
