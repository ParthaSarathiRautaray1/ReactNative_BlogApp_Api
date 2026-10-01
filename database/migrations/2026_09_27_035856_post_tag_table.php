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
        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('tag_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // This creates a composite primary key using both columns together.
            // bcoz we dont mention id in this table so combinely both of these act as a id like primary key
            // uniqueness character like ->unique method
            $table->primary(['post_id', 'tag_id']);

            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
