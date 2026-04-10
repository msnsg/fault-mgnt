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
        if(!Schema::hasTable('faults')){
            Schema::create('faults', function (Blueprint $table) {
                $table->id();
                $table->string('fault_reference', 191)->unique();
                $table->string('incident_title');
                $table->unsignedInteger('category_id');
                $table->decimal('lat', 10, 7);
                $table->decimal('long', 10, 7);
                $table->text('description')->nullable();
                $table->timestamp('incident_time');
                $table->timestamps();

                $table->index('category_id');
                $table->index('created_at');

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faults');
    }
};
