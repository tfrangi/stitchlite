<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVariants extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('variants', function(Blueprint $table)
		{
			$table->increments('id');
			$table -> string('sku', 32);
			$table -> decimal('price', 10, 2) -> nullable();
			$table -> integer('inventory') -> nullable();
			$table -> integer('product_id') -> unsigned();
			$table->timestamps();

			$table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('variants');
	}

}
