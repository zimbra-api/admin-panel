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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->default(0)->index();
            $table->foreignId('domain_id')->default(0)->index();
            $table->foreignId('cos_id')->default(0)->index();
            $table->string('zimbra_cos_id')->nullable();
            $table->string('zimbra_id')->unique();
            $table->string('name')->unique();
            $table->enum('status', [
                'active',
                'maintenance',
                'locked',
                'closed',
                'lockout',
                'pending',
            ])->default('active')->index();
            $table->string('mail_host')->index();
            $table->string('display_name');
            $table->string('title')->nullable();
            $table->string('organization')->nullable();
            $table->string('organization_unit')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('zimbra_create')->nullable();
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->string('created_by')->nullable()->index();
            $table->string('updated_by')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('aliases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->default(0)->index();
            $table->foreignId('domain_id')->default(0)->index();
            $table->foreignId('account_id')->default(0)->index();
            $table->string('name')->unique();
            $table->string('zimbra_target_id');
            $table->timestamp('zimbra_create')->nullable();
            $table->string('created_by')->nullable()->index();
            $table->string('updated_by')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aliases');
        Schema::dropIfExists('accounts');
    }
};
