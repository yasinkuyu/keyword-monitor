<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Domain;
use App\Models\Keyword;
use App\Models\User;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keyword_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('position')->nullable();
            
            $table->string('country', 5)->nullable();
            $table->string('language', 5)->nullable();

            $table->foreignId('service_id')->nullable();
            $table->foreignId('keyword_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('domain_id')->nullable()->constrained()->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keyword_positions');
    }
};
