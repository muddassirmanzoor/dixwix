<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Default group delete delay (in days). Used when non-admin deletes a group.
        $exists = DB::table('settings')
            ->where('name', 'group_delete_days')
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('settings')->insert([
            'name' => 'group_delete_days',
            'value' => '90',
            'type' => 2, // number
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('name', 'group_delete_days')->delete();
    }
};

