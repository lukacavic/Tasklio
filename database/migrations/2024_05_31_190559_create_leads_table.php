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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('position')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('lost')->default(false);
            $table->string('company');
            $table->integer('client_id')->nullable();
            $table->timestamp('client_converted_at')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('source_id')->nullable();
            $table->string('country')->nullable();
            $table->string('website')->nullable();
            $table->integer('assigned_user_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('important')->default(false);
            $table->timestamp('last_contact_at')->nullable();
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
        Schema::dropIfExists('leads');
    }
};
