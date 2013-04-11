<?php

class Add_Authors {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('authors')->insert(array(
            'name'=>'BramCooreman',
            'bio' => 'blabla',
            'created_at'=> date('Y-m-d H:m:s'),
            'updated_at'=> date('Y-m-d H:m:s'),  
        ));
        DB::table('authors')->insert(array(
            'name'=>'Bram',
            'bio' => 'test',
            'created_at'=> date('Y-m-d H:m:s'),
            'updated_at'=> date('Y-m-d H:m:s'),  
        ));
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('authors')->where('name', '=', 'BramCooreman')->delete();
        DB::table('authors')->where('name', '=', 'Bram')->delete();
      
	}

}