<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');

            $table->smallInteger('status')->default(\App\Models\Employee::STATUS_PENDING);
            $table->string('name');
            $table->string('f_name')->nullable();
            $table->string('m_name')->nullable();
            $table->bigInteger('mobile')->unique();
            $table->string('password');
            $table->bigInteger('nid');
            $table->string('address');
            $table->text('photo')->nullable();

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
        Schema::dropIfExists('employees');
    }
}
