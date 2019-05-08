<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->integer('provider_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('voucher',['bill', 'note', 'credit'])->default('bill');
            $table->string('voucher_serie', 7)->nullable();
            $table->string('voucher_num', 10);
            $table->dateTime('date');
            $table->decimal('tax', 4, 2);
            $table->decimal('total', 11, 2);
            $table->string('status', 20);
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
        Schema::dropIfExists('purchases');
    }
}
