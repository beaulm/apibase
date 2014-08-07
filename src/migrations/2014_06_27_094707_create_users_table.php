<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table) {
			$table->increments('id');
			$table->string('name', 64)->nullable();
			$table->string('phone', 16)->nullable();
			$table->string('email', 320)->nullable()->unique();
			$table->string('password', 64)->nullable()->index();
			$table->string('role', 32)->nullable()->index();
			$table->string('token', 128)->nullable()->index();
			$table->string('remember_token', 100)->nullable()->index();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
