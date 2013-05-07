<?php

class UusiMaksu_Controller extends Base_Controller
{    
    public $restful = true;
    
    public function get_index()
    {
        Session::put('sivu','uusiMaksu');
         $this->layout->nest('content', 'uusimaksu.index');
       // return View::make('uusimaksu.index');
        /*if (Input::has('jatka') && !$errorText) {
	
		echo "	<div id='uusiMaksuLomake' class='content padding20'>
				<p id='uusiMaksu'>".  Functions::localize('Hyväksy maksu')."</p>";
			
		echo "<table id='hyvaksyTable'>";
			Functions::getPossibleTableRow(Functions::localize('Maksajan tili'), $maksaja);
			Functions::getPossibleTableRow(Functions::localize('Maksajan nimi'), $maksajanNimi, false, true);
			Functions::getPossibleTableRow(Functions::localize('Saajan tilinumero'), $saaja, true);		
			Functions::getPossibleTableRow(Functions::localize('Saajan nimi'), $saajanNimi, false, true);
			Functions::getPossibleTableRow(Functions::localize('Eräpäivä'), $maksunErapaiva);
			Functions::getPossibleTableRow(Functions::localize('Maksun määrä'), number_format($summa, 2, ',', ' '), true);
			Functions::getPossibleTableRow(Functions::localize('Viite'), $viite);
			Functions::getPossibleTableRow(Functions::localize('Viesti'), $viesti);
		echo "</table>";
		
		echo "<form action='' method='post'>";
			Functions::getPossibleHiddenField($maksupvm, "maksupvm");
			Functions::getPossibleHiddenField($maksunErapaiva, "maksunErapaiva");
			Functions::getPossibleHiddenField($maksajanNimi, "maksajanNimi");
			Functions::getPossibleHiddenField($saajanNimi, "saajanNimi");
			Functions::getPossibleHiddenField($maksaja, "maksajanTili");
			Functions::getPossibleHiddenField($saaja, "saajanTili");
			Functions::getPossibleHiddenField($viite, "viite");
			Functions::getPossibleHiddenField($viesti, "viesti");
			Functions::getPossibleHiddenField($summa, "summa");
			
			echo "<p id='painikkeet'>
						<input type='submit' name='muuta' value='<< ".Functions::localize('MUUTA TIETOJA')."' class='painike'> 
						<input type='submit' name='hyvaksyMaksu' value=".Functions::localize('HYVÄKSY')." class='painike'> 
				</p>";
		echo "</form></div><!-- uusimaksulomake -->";
       // echo "This is the profile page.";
        }  else {
          //return View::make('uusimaksu.index');//->with('values', array('errorText'=>'','maksunErapaiva' => ''));
           //echo 'test'; 
          
        }*/
    }
    
    public function post_index()
    {
        $errorText = '';
        $maksunErapaiva = '';
        $maksupvm = ''; 
        $maksajanNimi = '';  
        $saajanNimi = ''; 
        $maksaja = '';        
        $saaja = ''; 
        $viite = ''; 
        $viesti = '';
        $summa = '';
        if(Input::has('jatka') || Input::has('muuta') || !Input::has('tyhjenna')){
            //DB::connection();
            $maksunErapaiva = mysql_real_escape_string(Input::get( 'maksunErapaiva'));
            $maksupvm = mysql_real_escape_string(substr($maksunErapaiva, 6, 4) . '-' . substr($maksunErapaiva, 3, 2) . '-' . substr($maksunErapaiva, 0, 2));
            $maksajanNimi = mysql_real_escape_string(Input::get( 'maksajanNimi' ));
            $saajanNimi = mysql_real_escape_string(Input::get( 'saajanNimi' ));
            $maksaja = mysql_real_escape_string(Input::get( 'maksajanTili' ));
            $saaja = mysql_real_escape_string(Input::get( 'saajanTili' ));
            $viite = mysql_real_escape_string(Input::get( 'viite' ));
            $viesti = mysql_real_escape_string(Input::get( 'viesti' ));
            $summa = mysql_real_escape_string(str_replace(',','.',Input::get( 'summa' )));

            
            if (empty($saaja)) {
                    $errorText = Functions::localize('Tilinumero on virheellinen tai tyhjä.');
            }

            // maksulla on oltava viite tai viesti
            if(!$viite && !$viesti){
                    $errorText = Functions::localize('Maksulla on oltava viite tai viesti.');
            }

            // summan pitää olla yli 0
            if ($summa <= 0) {
                    $errorText = Functions::localize('Syötä maksun summa.');
            }

            // saajan nimi on tyhjä
            if(!$saajanNimi){
                    $errorText = Functions::localize('Anna saajan nimi.');
            }

            // maksun määrässä voi olla vain numeroita
            if(!Functions::isDataNumeric($summa)){
                    $errorText = Functions::localize('Tarkista summa.');
            }
            
            else if ($maksupvm == date('Y-m-d')) {
                    if ($summa > Functions::getSaldo($maksaja, $maksupvm)) {
                            // rahaa ei ole tarpeeksi
                            $errorText = Functions::localize('Tilin saldo ei riitä maksuun.');
                    } else {
                            // rahaa on tarpeeksi, ei toimintoja
                    }
            }

            // päivämäärän tarkistus
            if(!Functions::checkDateFormat($maksunErapaiva)){
                    $errorText = Functions::localize('Tarkista päivämäärä.');
            }

            // tarkistetaan ettei eräpäivä ole jo mennyt
            if(strtotime($maksunErapaiva) < strtotime(date('d.m.Y'))){
                    $errorText = Functions::localize('Valitsemasi eräpäivä on jo mennyt');
            }
            
            if ($saaja == $maksaja) {
                    $errorText = Functions::localize('Et voi maksaa omalle tilillesi.');
            }
            
            
            if (substr($saaja, 0, 2) == 'FI' || substr($saaja, 0, 2) == 'GB' || substr($saaja, 0, 2) == 'SE') {
                  $uusiMaksu = Pankkitili::accountNumber($saaja);
                   // $result = mysql_query($query);

                    if ($uusiMaksu['count'] == 0) {
                            // tilinumeroa ei ole olemassa
                            $errorText = Functions::localize('Tilinumero on virheellinen.');
                    } else {
                                    $row = $uusiMaksu['result'];
                    }
            }
            else {
                    $errorText = Functions::localize('Tarkista saajan tilinumero.');			
            }	
    }

    $values =  array('errorText'=>$errorText, 'maksunErapaiva'=> $maksunErapaiva,
           'maksupvm' => $maksupvm, 'maksajanNimi' => $maksajanNimi,'saajanNimi' => $saajanNimi, 'maksaja' => $maksaja,
           'saaja' => $saaja, 'viite' => $viite, 'viesti' => $viesti, 'summa' => $summa );
    return View::make('home.index')
                ->nest('content', 'uusimaksu.index', $values);
    //View::make('uusimaksu.index')->with('test','haha');
   // $this->layout->with('Test','uusimaksu.index');
    //echo 'Eerror'.$errorText;
    /*with( 'values' , array('errorText'=>$errorText, 'maksunErapaiva'=> $maksunErapaiva,
           'maksupvm' => $maksupvm, 'maksajanNimi' => $maksajanNimi,'saajanNimi' => $saajanNimi, 'maksaja' => $maksaja,
           'saaja' => $saaja, 'viite' => $viite, 'viesti' => $viesti, 'summa' => $summa ) )*/
     //$this->layout->nest('content', 'uusimaksu.index', $values );
           
   // Redirect::back();
     /*  return View::make('uusimaksu.index')->with('values', array('errorText'=>$errorText, 'maksunErapaiva'=> $maksunErapaiva,
           'maksupvm' => $maksupvm, 'maksajanNimi' => $maksajanNimi,'saajanNimi' => $saajanNimi, 'maksaja' => $maksaja,
           'saaja' => $saaja, 'viite' => $viite, 'viesti' => $viesti, 'summa' => $summa ));
     */ //  echo "This is the profile page.";
    }
    
    public function __construct() {
        parent::__construct();
    }
}

