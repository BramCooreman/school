<?php

class Base_Controller extends Controller {

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
    
        public $layout = 'home.index';
    
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}
    
    public function __construct()
    {
        //Assets
        Asset::add('jquery', 'js/jquery-1.7.2.min.js');
        Asset::add('bootstrap-js', 'js/bootstrap.min.js');
        Asset::add('bootstrap-css', 'css/bootstrap.min.css');
        Asset::add('bootstrap-css-responsive', 'css/bootstrap-responsive.min.css', 'bootstrap-css');
        Asset::add('css','css/ainopankki_style.css');
        
        if(!Session::get( "Authenticated")){
            $this->filter('before', 'auth');
        }
        
        parent::__construct();
    }

}