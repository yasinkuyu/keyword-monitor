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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->boolean('requires_recaptcha')->default(false);
            $table->string('recaptcha_sitekey')->nullable();
        });

        // Varsayılan değerleri ekleyin
        DB::table('services')->insert([
            ['key' => 'google.selenium', 'name' => 'Google Selenium', 'requires_recaptcha' => false, 'recaptcha_sitekey' => null],
            ['key' => 'tools.seo.ai', 'name' => 'tools.seo.ai', 'requires_recaptcha' => true, 'recaptcha_sitekey' => '6Lcj8BkpAAAAAPzLvHsrB4zDD9v5HOe7pjYHXXp8'],
        ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
