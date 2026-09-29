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
        Schema::table('users', function (Blueprint $table) {
            // Add columns to match the user schema
            $table->string('username', 100)->after('id');
            $table->string('role', 10)->after('password');
            $table->string('status', 30)->after('role');

            // Drop columns that are not needed for the kerajinan schema
            $table->dropColumn(['name', 'email', 'email_verified_at', 'remember_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore original columns
            $table->string('name')->after('id');
            $table->string('email')->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->rememberToken()->after('password');

            // Drop added columns
            $table->dropColumn(['username', 'role', 'status']);
        });
    }
};
