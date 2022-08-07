<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('本の名前');
            $table->tinyInteger('status')->comment('読書状態');
            $table->string('author')->nullable()->comment('著者');
            $table->string('pabulication')->nullable()->comment('出版社');
            $table->date('read_at')->nullable()->comment('読み終わった日');
            $table->text('note')->nullable()->comment('メモ');
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
        Schema::dropIfExists('books');
    }
};
