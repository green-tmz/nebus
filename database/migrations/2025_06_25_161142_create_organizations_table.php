<?php

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Building::class)
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('organization_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organization::class)
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->string('phone');
            $table->timestamps();
        });

        // Промежуточная таблица между организациями и видами деятельности
        Schema::create('activity_organization', function (Blueprint $table) {
            $table->foreignIdFor(Activity::class)
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreignIdFor(Organization::class)
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('organization_phones');
        Schema::dropIfExists('activity_organization');
    }
};
