<?php
class Tilitapahtumat_Controller extends Base_Controller
{    
    public $restful = true;
    
    public function get_index()
    {
        Session::put('sivu','tilitapahtumat');
        $values = self::printLastTransactions();
        $this->layout->nest('content', 'tilitapahtumat.index', $values);
    }
    
    public function post_index()
    {
        $tilinSaldoAikavalilla = 0;
        $errorText ="";
        $tempSaldo = 0;
        if (Input::has( 'search' )) {
            $startDate = Input::get( 'startdate' );
            $endDate = Input::get( 'enddate');
            $startDate = Functions::checkDateFormat( $startDate );
            $endDate = Functions::checkDateFormat( $endDate );
            
            if( $startDate == false ){
                $errorText = Functions::localize('Tarkista alkupäivämäärä.');
            }
            // jos loppupvm ei ole annettu
            if( $endDate == false ){
                $errorText = Functions::localize('Tarkista loppupäivämäärä.');
            }

            $today = date('d.m.Y');
            // jos alkupvm on tulevaisuudessa
            if( strtotime($startDate) > strtotime($today) ) {
                $errorText = Functions::localize('Tarkista alkupäivämäärä.');
                $startDate = false;
            }
            // jos lopupvm on tulevaisuudessa
            if ( strtotime($endDate) > strtotime($today) ) {
                $errorText = Functions::localize('Tarkista loppupäivämäärä.');
                $endDate = false;
            }
           
            if( $startDate != false and $endDate != false and empty($errorText) ) {

                $yhtio = Session::get( 'yhtionNimi');
                $ytunnus = Session::get( 'ytunnus' );

                if(strtotime($endDate) > strtotime(date('d.m.Y'))){
                    $endDate = date('d.m.Y');
                }

               // print '<p class="yhtionTiedot"><strong>' . $yhtio . '</strong></p>';
                
                $bankAccount = Pankkitili::bankAccount($ytunnus);
                
                if($bankAccount['count'] == 0)
                {
                     // echo "<p>".Functions::localize('Pankkitiliä ei löydy')."</p>";    
                }
                else
                {
                    $tilinro = $bankAccount['result'][0]->tilinro;
                  //  echo "<table class='tilinTiedot'><tr><td>".Functions::localize('Tilinro:')."</td><td> $tilinro</td></tr>";
                    
                   // echo "<tr><td>".Functions::localize('Tilitapahtumat aikavälillä')." </td><td>$startDate - $endDate</td></tr></table>";

                    $startDateMySql = date( 'Y-m-d', strtotime($startDate) );
                    $endDateMySql = date( 'Y-m-d', strtotime( $endDate ) );
                    
                    $bankBalance = Pankkitapahtuma::bankBalance($tilinro, $startDateMySql, $endDateMySql);
                    
                    if ($bankBalance['count'] == 0) {
                       /*  echo "<div id='tilitapahtumat'>
                                <p>".Functions::localize('Tilitapahtumia ei löydy aikavälillä')." $startDate - $endDate !</p>
                                            ";*/
						
                    } else {

                        $dateTable = explode(".", $startDate);
                        $yesterday = mktime(0, 0, 0, $dateTable[1]  , $dateTable[0] -1, $dateTable[2] );

                     /*   echo "	<div class='content'>
                                <table id='tilioteTable'>
                                        <tr>
                                                <th>".Functions::localize('Tap.pvm')."</th>
                                                <th>".Functions::localize('Saajan nimi')."</th>
                                                <th>".Functions::localize('Maksajan nimi')."</th>
                                                <th>".Functions::localize('Summa')."</th>
                                                <th>".Functions::localize('Viite selite')."</th>
                                        </tr>
                                        <tr>
                                                <td></td>
                                                <td></td>
                                                <td>".Functions::localize('Alkusaldo')." $startDate</td>";
*/
                        // etumerkki alkusaldoon
                                $tempSaldo = Functions::getSaldo($tilinro, date('Y-m-d', $yesterday));
                                        if($tempSaldo>=0){
                                                $tempSaldo = "+".$tempSaldo;
                                        }

                    /*    echo "		<td class='alignRight'>$tempSaldo</td>
                                                <td></td>
                                        </tr>
                                ";*/



                        // tulostetaan sql-kyselystä saadut tulokset
                        /* $i = 1;
                        foreach($bankBalance['result'] as $row) {
                                $tapvm = $row->tapvm;
                                $saajanNimi = $row->saajannimi;
                                $maksajanNimi = $row->maksajannimi;
                                $summa = $row->summa;
                                $selite = $row->selite;
                                $viite = $row->viite;
                                if ( !empty($viite) ) {
                                        $viite = $viite . ",<br/>";
                                }
                                $maksaja = $row->maksaja;

                                echo "<tr";
                                        if ($i%2 == 1) echo " class='oddRow'";
                                        $i++;
                                echo ">
                                                <td>".date('d.m.Y',strtotime($tapvm))."</td>
                                                <td>$saajanNimi</td>
                                                <td>$maksajanNimi</td>
                                        ";

                                // jos laskun maksaja on sama kuin yrityksen oma tili, on kyse maksusta (eli tulostetaan miinusmerkki)
                                if ($row->maksaja == $tilinro) {
                                        echo "<td class='alignRight'>-$summa</td>";
                                        $tilinSaldoAikavalilla = $tilinSaldoAikavalilla - $summa;
                                } else {
                                        echo "<td class='alignRight'>+$summa</td>";
                                        $tilinSaldoAikavalilla = $tilinSaldoAikavalilla + $summa;
                                }

                               echo "
                                                <td>$viite $selite</td>
                                        </tr>
                                        ";
                        }*/
                    /*    echo "<tr>
                                        <td></td>
                                        <td></td>
                                        <td>".Functions::localize('Tapahtumat yhteensä')."</td>
                                        <td class='alignRight'>".number_format($tilinSaldoAikavalilla,2,'.',' ')."</td>
                                        <td></td>
                                </tr>
                                <tr>
                                        <td></td>
                                        <td></td>
                                        <td>".Functions::localize('Tilin saldo')." " . $endDate ."</td>";*/

                        // etumerkki saldoon
                        $tempSaldo = Functions::getSaldo($tilinro, date('Y-m-d', strtotime($endDate)));
                        if($tempSaldo>=0){
                                $tempSaldo = "+".$tempSaldo;
                    }
                    /*echo "
                            <td class='alignRight'>$tempSaldo</td>
                            <td></td>
                    </tr>
                    </table>";*/
                }
                $tilinSaldo = Functions::getSaldo($tilinro, date('Y-m-d'));
			
                    /*echo "<table class='tilinSaldot'>";

                    echo "<tr><td>".Functions::localize('Tilin saldo')." ".date('d.m.Y').": </td><td>";
                            if($tilinSaldo >= 0) {
                                    echo "+";
                            }
                    echo "$tilinSaldo ".Functions::localize('euroa')."</td></tr></table>";
                    echo "</div><!-- /tilitapahtumat -->
                                    <p><a class='painike' href='index.php?sivu=tilitapahtumat'>".Functions::localize('Takaisin')."</a></p>";*/
                }
              
            }
            
            else {
                $lastTransactions = self::printLastTransactions();
                    if($startDate == false && $endDate == false){
                      $values =  array_merge((array)$lastTransactions, array('startDate' => $startDate, 'endDate' => $endDate,'errorText' => $errorText));
          
                    }
                    elseif($startDate == false){
                         $values = array_merge((array)$lastTransactions, array('startDate' => $startDate, 'endDate' => $endDate,'errorText' => $errorText));
                    
                    }
                    elseif($endDate == false){
                         $values = array_merge((array)$lastTransactions, array('startDate' => $startDate, 'endDate' => $endDate,'errorText' => $errorText));
                           // printTimeFrameSearchForm();
                    
                    }
                    return View::make('home.index')
                                    ->nest('content', 'tilitapahtumat.index', $values); 
            }
                $values = array('startDate' => $startDate, 'endDate' => $endDate,'errorText' => $errorText, "bankAccount"=> $bankAccount, 
                  "bankBalance" => $bankBalance, 'tempSaldo' => $tempSaldo, 'tilinSaldo' => $tilinSaldo, 'tilinro' => $tilinro);
          return View::make('home.index')
                                    ->nest('content', 'tilitapahtumat.index', $values); 
        }
        else {
		//printTimeFrameSearchForm();
		//self::printLastTransactions();
	}
    }
    
    function printLastTransactions() 
    {
   	$ytunnus = Session::get( 'ytunnus');
        $values = array();
        $tilinSaldoAikavalilla = 0;
        $bankAccount = Pankkitili::bankAccount($ytunnus);
	
	// Tilinumeroa ei löydy (ei pitäisi koskaan tapahtua)
        if ($bankAccount['count'] != 0) {
              $tilinro = $bankAccount['result'][0]->tilinro;
              $organizationInfo = Pankkitapahtuma::organizationInfo($tilinro);
        }
        
	return $values = array('bankAccount' => $bankAccount,
            'organizationInfo' => $organizationInfo, 'tilinro' => $tilinro);
        
                         /*  return View::make('home.index')
                                    ->nest('content', 'tilitapahtumat.index', $values); // printTimeFrameSearchForm();
                    	*/
        	
    }
    
    public function __construct() {
        parent::__construct();
    }
}
