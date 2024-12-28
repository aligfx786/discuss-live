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
        Schema::create('post_user', function (Blueprint $table) {
            // $table->id();
            // In a pivot table, we often don't need an auto-incrementing ID because:
            // The combination of user_id and post_id already uniquely identifies each record
            // It prevents duplicate relationships (a user can't repost the same post multiple times)
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('reposted_at')->nullable();
            // this approach (composite primary key):
            // Ensures each user can repost a post only once
            // More space-efficient
            $table->primary(['user_id', 'post_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_user');
    }
};
