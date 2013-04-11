<?php

class Create_Urls_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('urls', function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('url');
            $table->string('description');
            $table->timestamps();
        });
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}