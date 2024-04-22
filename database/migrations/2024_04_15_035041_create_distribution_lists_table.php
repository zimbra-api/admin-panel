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
        Schema::create('distribution_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->index();
            $table->foreignId('domain_id')->index();
            $table->foreignId('user_id')->default(0)->index();
            $table->string('zimbra_id')->unique();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->unsignedMediumInteger('total_members')->default(0);
            $table->timestamp('zimbra_create')->nullable();
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->unsignedInteger('created_by')->default(0)->index();
            $table->unsignedInteger('updated_by')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('dlist_hierarchy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dlist_id')->index();
            $table->foreignId('parent_id')->default(0)->index();
        });

        Schema::create('dlist_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dlist_id')->index();
            $table->foreignId('account_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dlist_members');
        Schema::dropIfExists('dlist_hierarchy');
        Schema::dropIfExists('distribution_lists');
    }
};
