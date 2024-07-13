<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (Project::all() as $project) {
            $project->update(['slug' => Str::slug($project->name)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (Project::all() as $project) {
            $project->update(['slug' => null]);
        }
    }
};
