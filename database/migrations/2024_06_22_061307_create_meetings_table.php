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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('user_id');
            $table->timestamp('meeting_from')->nullable();
            $table->timestamp('meeting_to')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('finished_note')->nullable();
            $table->integer('project_id')->nullable();
            $table->json('tags')->nullable();
            $table->integer('organisation_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
