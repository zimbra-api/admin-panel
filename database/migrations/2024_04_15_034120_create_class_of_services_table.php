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
        Schema::create('class_of_services', function (Blueprint $table) {
            $table->id();
            $table->string('zimbra_id')->unique();
            $table->string('name');
            $table->unsignedBigInteger('mail_quota')->default(0);
            $table->unsignedMediumInteger('max_accounts')->default(0);
            $table->timestamp('zimbra_create')->nullable();
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->unsignedInteger('created_by')->default(0)->index();
            $table->unsignedInteger('updated_by')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_of_services');
    }
};
