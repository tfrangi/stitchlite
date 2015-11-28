<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductShop extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_shop', function(Blueprint $table)
		{
			$table->increments('id');
			$table -> integer('product_id') -> unsigned();
			$table -> integer('shop_id') -> unsigned();
			$table->timestamps();

			$table -> foreign('product_id') -> references('id') -> on('products') -> onUpdate('cascade') -> onDelete('cascade');
			$table -> foreign('shop_id') -> references('id') -> on('shops') -> onUpdate('cascade') -> onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_shop');
	}

}
