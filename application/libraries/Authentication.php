<?php

class Authentication extends Laravel\Auth\Drivers\Driver {

    public function attempt($arguments = array())
    {
        $username = $arguments['username'];
        $password = $arguments['password'];

        $result = Functions::loginInDB($username, $password);

        if($result)
        {
            return $this->login($result->tunnus, array_get($arguments, 'remember'));
        }

        return false;
    }

    public function retrieve($id)
    {
        echo "test";
    }
    
 

}
?>
