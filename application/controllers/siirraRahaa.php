<?php

class SiirraRahaa_Controller extends Base_Controller
{   
     public $restful = true;
    
    public function get_index()
    {
        Session::put('sivu','siirraRahaa');
        $this->layout->nest('content', 'siirraRahaa.index');
    }
    
    public function post_index()
    {
        $errorText = '';
        $maksunErapaiva = ''; 
        $saajanNimi = '';    
        $saaja = ''; 
        $viesti = '';
        $summa = '';
        
        if(Input::has('jatka')) {
            $saaja = mysql_real_escape_string(Input::get('saajanTili'));
            $saajanNimi = mysql_real_escape_string(Input::get( 'saajanNimi'));
            $summa = mysql_real_escape_string(str_replace(',','.',Input::get('summa' )));
            $maksunErapaiva = date("d.m.Y");
            $viesti = Functions::localize('Ylläpitäjän suorittama rahan siirto');

            if (empty($saaja)) {
                    $errorText = Functions::localize('Tilinumero on virheellinen tai tyhjä.');
            }

            if ($summa <= 0) {
                    $errorText = Functions::localize('Syötä maksun summa.');
            }

            if (!$saajanNimi) {
                    $errorText = Functions::localize('Anna saajan nimi.');
            }

            if(!Functions::isDataNumeric($summa)){
                    $errorText = Functions::localize('Tarkista summa.');
            }
            if (substr($saaja, 0, 2) == 'FI') {
                $accountNumber = Pankkitili::accountNumber($saaja);
                
                if ($accountNumber['count'] == 0) {
                        // tilinumeroa ei ole olemassa
                        $errorText = Functions::localize('Tilinumero on virheellinen.');
                } else {
                                $row = $accountNumber['result'];
                }
            }
            else {
                    $errorText = Functions::localize('Tarkista saajan tilinumero.');
            }
        }
          $values =  array('errorText'=>$errorText, 'maksunErapaiva'=> $maksunErapaiva,
            'saajanNimi' => $saajanNimi, 'saaja' => $saaja,
              'viesti' => $viesti, 'summa' => $summa );
        return View::make('home.index')
                ->nest('content', 'siirraRahaa.index', $values);
    }
    
    public function __construct() {
        parent::__construct();
    }
}
?>
