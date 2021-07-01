<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateMinHashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Capsule::schema()->create('min_hashes', function (Blueprint $table) {
            $table->id();
            $table->string('hash_id', 32)->default('');
            $table->bigInteger('user_id')->nullable();
            $table->string('hash', 20)->default('');
            $table->text('data');
            $table->integer('expire_date')->default(0);
            $table->timestamps();
            $table->index('hash', 'min_hash_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('min_hashes');
    }
}
