<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id');
            $table->integer('area_id');
            $table->integer('connection_point_id')->nullable();
            $table->integer('employee_id')->nullable();

            $table->string('name');
            $table->string('f_name')->nullable();
            $table->string('m_name')->nullable();
            $table->bigInteger('mobile')->unique();
            $table->bigInteger('nid')->nullable();
            $table->smallInteger('status')->default(\App\Models\Customer::STATUS_PENDING);
            $table->string('address')->nullable();
            $table->string('net_user')->nullable();
            $table->string('net_pass')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
