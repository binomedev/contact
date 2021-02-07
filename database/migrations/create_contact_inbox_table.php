<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactInboxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_inbox', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->nullable();
            $table->string('subject');
            $table->text('content');
            $table->string('to');
            $table->string('from');
            $table->string('mailable')->nullable();
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('contact_inbox');
    }
}
