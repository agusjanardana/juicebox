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
        // change user to users
        Schema::rename('user', 'users');
        Schema::rename('note', 'notes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::rename('users', 'user');
        Schema::rename('notes', 'note');
    }
};