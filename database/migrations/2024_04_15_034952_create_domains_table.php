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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->default(0)->index();
            $table->foreignId('user_id')->default(0)->index();
            $table->string('admin_user');
            $table->string('admin_password');
            $table->string('zimbra_id')->unique();
            $table->string('name')->unique();
            $table->enum('status', [
                'active',
                'maintenance',
                'locked',
                'closed',
                'suspended',
                'shutdown',
            ])->default('active');
            $table->unsignedMediumInteger('max_accounts')->default(0);
            $table->unsignedMediumInteger('total_accounts')->default(0);
            $table->timestamp('zimbra_create')->nullable();
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->unsignedInteger('created_by')->default(0)->index();
            $table->unsignedInteger('updated_by')->default(0)->index();
            $table->timestamps();
        });
 
        Schema::create('domain_coses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->default(0)->index();
            $table->foreignId('cos_id')->default(0);
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
