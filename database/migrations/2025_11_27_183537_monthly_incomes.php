<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('monthly_incomes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->year('year');
        $table->tinyInteger('month'); // 1–12
        $table->decimal('amount', 12, 2); // penghasilan utama
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('monthly_incomes');
}

};
