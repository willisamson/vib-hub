<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 40)->default('pruefung');
            $table->string('icon', 40)->default('sine');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->json('features')->nullable();
            $table->string('demo', 40)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('level', 40)->default('einsteiger');
            $table->string('format', 40)->default('praesenz');
            $table->string('duration', 60)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->json('topics')->nullable();
            $table->json('dates')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('norms', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category', 40)->default('allgemein');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->json('parameters')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone', 60)->nullable();
            $table->string('topic', 40)->default('sonstiges');
            $table->string('reference')->nullable();
            $table->text('message');
            $table->string('status', 20)->default('neu');
            $table->text('notes')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('norms');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('services');
    }
};
