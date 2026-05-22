<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresenceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presence_logs', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('photo')->nullable();
            $table->string('status')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_in_office')->default(false);
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
        Schema::dropIfExists('presence_logs');
    }
}
