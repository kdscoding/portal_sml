<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_label_sbsite', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->primary();
            $table->integer('no_urut');
            $table->string('upload_version');
            $table->string('id_sb_site');
            $table->string('id_vendor');
            $table->string('po');
            $table->string('item');
            $table->string('country');
            $table->string('building');
            $table->string('cell');
            $table->date('sdd');
            $table->integer('qty');
            $table->boolean('status_received')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_label_sbsite');
    }
};
