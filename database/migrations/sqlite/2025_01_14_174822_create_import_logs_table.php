<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('sqlite_import_logs')->create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_id');
            $table->text('value')->nullable();
            $table->integer('row_number')->nullable();
            $table->text('error_message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('sqlite_import_logs')->dropIfExists('import_logs');
    }
};
