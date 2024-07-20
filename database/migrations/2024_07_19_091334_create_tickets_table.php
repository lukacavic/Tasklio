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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('title');
            $table->smallInteger('status_id')->default(1);
            $table->integer('contact_id')->nullable();
            $table->string('email')->nullable();
            $table->integer('assigned_user_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('user_id');
            $table->timestamp('last_reply_at')->nullable();
            $table->timestamp('closed_at')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
