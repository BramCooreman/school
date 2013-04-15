<?php

class Login_Controller extends Base_Controller
{    
    public function action_index()
    {
        
    }
    
    public $restful = true;
 
    public function post_authenticate()
    {
        //all POST requests to /api/authenticate will go here
        //any other requests that are NOT POST will NOT go here.
        echo "test";
    }
    
   
    public function get_user()
    {
       // echo Input::get('lang');
      // echo  $user_id = Input::get('username');
        //get the USER based on $user_id and return it for whoever requested it      
        
       echo "test";

        //echo $user->email;
    }
     
    public function post_user()
    {
        $email = Input::get('email');
        $password = Input::get('password');
        
        echo "test";
        
      //  echo  $user_id = Input::get('username');
        //Create a new User
        //$user = User::create($user_id, $password);
        
    }           
}