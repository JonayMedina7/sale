<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retentions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->string('voucher_num', 20);
            $table->date('date');
            $table->string('year', 7);
            $table->string('month', 11);
            $table->decimal('total', 15, 2);
            $table->string('status', 20);
            $table->timestamps();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retentions');
    }
}
