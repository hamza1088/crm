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
            $table->increments('id');
            $table->string('first_name', 50)->nullable(false);
            $table->string('last_name', 50)->nullable(false);
            $table->integer('company_id')->unsigned();
            $table->string('email', 100);
            $table->string('phone', 100);
            $table->integer('status');
            $table->timestamps();
        });


       Schema::table('employees', function($table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
