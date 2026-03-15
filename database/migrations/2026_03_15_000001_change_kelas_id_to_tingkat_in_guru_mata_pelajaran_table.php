<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the actual foreign key name
        $foreignKey = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru_mata_pelajaran' 
            AND CONSTRAINT_TYPE = 'FOREIGN KEY' LIMIT 1");
        
        if (!empty($foreignKey)) {
            $fkName = $foreignKey[0]->CONSTRAINT_NAME;
            // Drop foreign key
            DB::statement("ALTER TABLE guru_mata_pelajaran DROP FOREIGN KEY {$fkName}");
        }
        
        // Now we can safely drop the unique index
        DB::statement('ALTER TABLE guru_mata_pelajaran DROP INDEX guru_mapel_kelas_unique');
        
        // Rename column and change type
        DB::statement('ALTER TABLE guru_mata_pelajaran CHANGE COLUMN kelas_id tingkat VARCHAR(255)');
        
        // Add new unique constraint
        DB::statement('ALTER TABLE guru_mata_pelajaran ADD UNIQUE KEY guru_mapel_tingkat_unique (guru_id, mata_pelajaran_id, tingkat)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new unique constraint
        DB::statement('ALTER TABLE guru_mata_pelajaran DROP INDEX guru_mapel_tingkat_unique');
        
        // Change tingkat back to kelas_id
        DB::statement('ALTER TABLE guru_mata_pelajaran CHANGE COLUMN tingkat kelas_id BIGINT UNSIGNED');
        
        // Get kelas_rapor id for foreign key restoration
        DB::statement('ALTER TABLE guru_mata_pelajaran ADD CONSTRAINT guru_mata_pelajaran_kelas_id_foreign FOREIGN KEY (kelas_id) REFERENCES kelas_rapor (id) ON DELETE CASCADE');
        
        // Restore original unique constraint
        DB::statement('ALTER TABLE guru_mata_pelajaran ADD UNIQUE KEY guru_mapel_kelas_unique (guru_id, mata_pelajaran_id, kelas_id)');
    }
};
