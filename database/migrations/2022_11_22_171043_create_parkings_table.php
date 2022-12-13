<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['IN', 'OUT']);
            $table->integer('helm')->default(0);
            $table->boolean('is_expired')->default(false);
            $table->unsignedBigInteger('id_kendaraan');
            $table->unsignedBigInteger('id_karyawan');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_kendaraan')
                ->references('id')
                ->on('vehicles')
                ->onDelete('cascade');
            $table->foreign('id_karyawan')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parkings');
    }
};
