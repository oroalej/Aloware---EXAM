<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (! Schema::hasTable('comments')) {
            Schema::create('comments', static function( Blueprint $table ){
                $table->id();
                $table->string('name');
                $table->string('message');
                $table->tinyInteger('depth');
                $table->unsignedBigInteger('parent_id')->nullable();

                $table->foreign('parent_id')
                    ->references('id')
                    ->on('comments');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
