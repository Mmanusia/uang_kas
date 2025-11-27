<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('budgets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
        $table->tinyInteger('month');     // 1-12
        $table->year('year');
        $table->decimal('limit_percentage', 5, 2)->nullable(); // ex: 40.50%
        $table->decimal('limit_amount', 12, 2)->nullable();    // ex: 2000000
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('budgets');
}

};
