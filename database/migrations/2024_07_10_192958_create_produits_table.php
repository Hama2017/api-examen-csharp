<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('CodeProduit',50);
            $table->string('DesignationProduit',100);
            $table->double('PU')->nullable();
            $table->integer('QteMin')->nullable();
            $table->integer('QteCri')->nullable();
            $table->string('CodeCategorie',100);
        });
    }

    public function down()
    {
        Schema::dropIfExists('produits');
    }
}

