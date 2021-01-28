<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');

            $table->integer('customer_id');
            $table->integer('employee_id')->nullable();
            $table->double('amount');
            $table->smallInteger('type')->default(\App\Models\Payment::TYPE_CASH);
            $table->smallInteger('status')->default(\App\Models\Payment::STATUS_PENDING);

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
        Schema::dropIfExists('payments');
    }
}
