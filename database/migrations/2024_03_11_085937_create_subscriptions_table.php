<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('birth');
            $table->string('ID_personal');
            $table->string('address');
            $table->string('phone1');
            $table->string('phone2');
            $table->string('email');
            $table->string('factory_name');
            $table->string('factory_ent');
            $table->string('Industry_name')->default('some_default_value');
            $table->integer('ID_classification');
            $table->float('Money');
            $table->float('Num_Worker');
            $table->float('Value_equipment'); 
            $table->float('Num_Year_Worker');
            $table->float('Num_Exce');
            $table->float('Q_Water');
            $table->float('Q_Electricity');
            $table->integer('state_checked');
            $table->integer('state_approval');
            $table->integer('state_complated');
            $table->integer('state_specialize');
            $table->integer('state_cancelled');
            $table->integer('state_data'); 
            $table->string('payment_method');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('earth_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('earth_id')->references('id')->on('earths');
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
        Schema::dropIfExists('subscriptions');
    }
}
