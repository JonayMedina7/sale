<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->integer('client_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->enum('voucher',['bill', 'note', 'credit'])->default('bill');
            $table->string('voucher_serie', 7)->nullable();
            $table->string('voucher_num', 10);
            $table->dateTime('date');
            $table->decimal('tax', 4, 2);
            $table->decimal('total', 15, 2);
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
        Schema::dropIfExists('sales');
    }
}
