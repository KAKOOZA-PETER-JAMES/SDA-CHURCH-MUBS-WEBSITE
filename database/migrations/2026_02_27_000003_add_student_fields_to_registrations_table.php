<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('category', 20)->default('Other')->after('address');
            $table->string('year_of_study', 40)->nullable()->after('category');
            $table->string('program_name', 120)->nullable()->after('year_of_study');
            $table->unsignedSmallInteger('year_of_entry')->nullable()->after('program_name');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['category', 'year_of_study', 'program_name', 'year_of_entry']);
        });
    }
};
