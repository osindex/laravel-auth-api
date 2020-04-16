<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_permissions', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('name');
            $table->string('device');
            $table->string('router')->nullable();
            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
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
        Schema::dropIfExists('api_permissions');
    }
}
