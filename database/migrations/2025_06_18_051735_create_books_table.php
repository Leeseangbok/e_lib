<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('openlibrary_id')->unique();
            $table->string('title');
            $table->json('authors');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('subjects')->nullable();
            $table->integer('page_count')->nullable();
            $table->string('isbn')->nullable();
            $table->date('publish_date')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('ratings_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};
