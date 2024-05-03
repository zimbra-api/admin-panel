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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->default(0)->index();
            $table->string('name');
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->string('organization')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('created_by')->default(0)->index();
            $table->unsignedInteger('updated_by')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('agency_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique();
            $table->foreignId('agency_id')->default(0)->index();
        });

        Schema::create('agency_mail_hosts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->default(0)->index();
            $table->foreignId('mail_host_id')->default(0);
        });

        Schema::create('agency_coses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->default(0)->index();
            $table->foreignId('cos_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_coses');
        Schema::dropIfExists('agency_mail_hosts');
        Schema::dropIfExists('agency_members');
        Schema::dropIfExists('agencies');
    }
};
