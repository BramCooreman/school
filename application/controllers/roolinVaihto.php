<?php
class RoolinVaihto_Controller extends Base_Controller
{   
     public $restful = true;
    
    public function get_index()
    {
        Session::put('sivu','roolinVaihto');
        $user = Session::get( 'kayttaja');
        $potentialCompanies = Kuka::potentialCompanies($user);
        $values = array('potentialCompanies' => $potentialCompanies);
        $this->layout->nest('content', 'roolinVaihto.index', $values);
    }
    
    public function post_index()
    {
     
       if(Input::has('vaihdaRooli')){
            $role = Input::get('asiakasrooli'); /**< INT, Y-tunnus, Käyttäjän valitsema rooli */
            $user = Session::get( 'kayttaja' ); /**< STRING, Nimi, Käyttäjän sen hetkinen rooli */
            $potentialCompanies = Kuka::potentialCompanies($user);
            $values = array('potentialCompanies' => $potentialCompanies);

            $resultSet = Kuka::changeRole($user,$role);
            $row = $resultSet['result'];

            // Tarkastetaan saadaanko tuloksia kyselylle ja vaihdetaan roolin tiedot käyttäjän tiedoiksi
            //Check the results of the query is received and the exchange of information on the role of user data
            $num_rows = $resultSet['count'];
            if ( $num_rows != 0 ) {
                    Session::put('ytunnus', $row[0]->ytunnus);
                    Session::put('yhtionNimi', $row[0]->yhtionnimi);
                    Session::put('tilinro', $row[0]->tilinro);

                     $this->layout->nest('content', 'roolinVaihto.index', $values);
                    //header("Location: roolinVaihto");
                    //echo "<p>Asiakasrooli vaihdettu!</p>";

            }
            else {
                $this->layout->nest('content', 'roolinVaihto.index', $values);
                    //header("Location: roolinVaihto");
                    //echo "<p>".Functions::localize('Virhe vaihdettaessa asiakasroolia!')."</p>";
            }
        }
    }
   
    public function __construct() {
        parent::__construct();
    }
}
