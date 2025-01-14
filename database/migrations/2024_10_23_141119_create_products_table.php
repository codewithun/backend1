<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('namaProduk');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('kodeProduk');
            $table->string('kategori');
            $table->integer('stok');
            $table->decimal('hargaJual', 10, 2);
            $table->text('keterangan')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
