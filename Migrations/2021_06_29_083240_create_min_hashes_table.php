<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMinHashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Capsule::schema()->hasTable('min_hashes')) {
            Capsule::schema()->create('min_hashes', function (Blueprint $table) {
                $table->id();
                $table->string('HashId', 32)->default('');
                $table->bigInteger('UserId')->nullable();
                $table->string('Hash', 20)->default('');
                $table->text('Data');
                $table->integer('ExpireDate')->default(0);
                $table->timestamp(\Aurora\System\Classes\Model::CREATED_AT)->nullable();
                $table->timestamp(\Aurora\System\Classes\Model::UPDATED_AT)->nullable();
                $table->index('Hash', 'min_hash_index');
            });
        } else {
            Capsule::schema()->table('min_hashes', function (Blueprint $table) {
                $table->renameColumn('hash_id', 'HashId', 32)->default('');
                $table->renameColumn('user_id', 'UserId')->nullable();
                $table->renameColumn('hash', 'Hash', 20)->default('');
                $table->renameColumn('data', 'Data');
                $table->renameColumn('expire_date', 'ExpireDate')->default(0);
                $table->timestamp(\Aurora\System\Classes\Model::CREATED_AT)->nullable();
                $table->timestamp(\Aurora\System\Classes\Model::UPDATED_AT)->nullable();
                $table->index('Hash', 'min_hash_index');
            });
        }
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
