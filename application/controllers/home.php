<?php

class Home_Controller extends Base_Controller {

	/*
	|--------------------------------------------------------------------------
	| The Default Controller
	|--------------------------------------------------------------------------
	|
	| Instead of using RESTful routes and anonymous functions, you might wish
	| to use controllers to organize your application API. You'll love them.
	|
	| This controller responds to URIs beginning with "home", and it also
	| serves as the default controller for the application, meaning it
	| handles requests to the root of the application.
	|
	| You can respond to GET requests to "/home/profile" like so:
	|
	|		public function action_profile()
	|		{
	|			return "This is your profile!";
	|		}
	|
	| Any extra segments are passed to the method as parameters:
	|
	|		public function action_profile($id)
	|		{
	|			return "This is the profile for user {$id}.";
	|		}
	|
	*/
    public $restful = true;
    
    public function get_index()
    {
        Session::put('ainopankki','');
        if(Input::has("sivu"))
        {
            $sivu = Input::get("sivu");
        }
        $sivu = Input::get("sivu");
        
        if(empty($sivu))
            $sivu = "etusivu";
        return View::make('home.index')->with('sivu',$sivu);
    }
    
    public function action_sivu()
    {
        echo 'test';
    }
    
    public function action_about()
    {
        return View::make('home.about', array(
            'sidenav' => array(
                array(
                    'url' => 'home',
                    'name' => 'Home',
                    'active' => false
                ),
                array(
                    'url' => 'about',
                    'name' => 'About',
                    'active' => true
                )
            )
        ));
    }
    
    //Redirect first to authentication and then continue
//    public function __construct() 
//    {
//        $this->filter('before', 'auth');
//    }

}