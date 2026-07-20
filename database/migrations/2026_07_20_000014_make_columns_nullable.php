<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fname')->nullable()->change();
            $table->string('lname')->nullable()->change();
            $table->string('mobile')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->string('role')->nullable()->change();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('mobile')->nullable()->change();
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->text('message')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fname')->nullable(false)->change();
            $table->string('lname')->nullable(false)->change();
            $table->string('mobile')->nullable(false)->change();
            $table->string('gender')->nullable(false)->change();
            $table->string('role')->nullable(false)->change();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('mobile')->nullable(false)->change();
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->text('message')->nullable(false)->change();
        });
    }
};
