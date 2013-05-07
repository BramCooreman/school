<?php

class Home_Controller extends Base_Controller {

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
        Session::put('sivu', $sivu);
        return View::make('home.index');
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
    public function __construct() 
    {
        $this->filter('before', 'login');
    }

}