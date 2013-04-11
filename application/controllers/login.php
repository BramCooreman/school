<?php

class Login_Controller extends Base_Controller
{    
    public function action_index()
    {
        //do our login mechanisms here
        echo 'test'; //echo test so we can test this controller out
    }
    
    public $restful = true;
 
    public function post_authenticate()
    {
        //all POST requests to /api/authenticate will go here
        //any other requests that are NOT POST will NOT go here.
    }
     
    public function get_user()
    {
        $user_id = Input::get('id');
        //get the USER based on $user_id and return it for whoever requested it
    }
     
    public function post_user()
    {
        $email = Input::get('email');
        $password = Input::get('password');
 
        //Create a new User
        $user = User::create($email, $password);
    }
}