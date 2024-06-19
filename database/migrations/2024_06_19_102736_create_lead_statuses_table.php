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
        Schema::create('lead_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->integer('order');
            $table->boolean('is_client')->default(false);
            $table->boolean('is_created')->default(false);
            $table->integer('project_id')->unsigned()->nullable();
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
        Schema::dropIfExists('lead_statuses');
    }
};
