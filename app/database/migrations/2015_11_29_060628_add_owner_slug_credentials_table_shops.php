<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnerSlugCredentialsTableShops extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shops', function(Blueprint $table)
		{
			$table -> string('short_name', 16)-> after('name');
			$table -> string('owner', 32) -> after('short_name');
			$table -> text('credentials') -> after('owner');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shops', function(Blueprint $table)
		{
			$table -> dropColumn(['short_name', 'owner', 'credentials']);
		});
	}

}
