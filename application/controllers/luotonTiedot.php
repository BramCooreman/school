<?php
class LuotonTiedot_Controller extends Base_Controller
{    
    public $restful = true;
    
    public function get_index()
    {
        $tilinro = Session::get('tilinro');
        Session::put('sivu','luotonTiedot');
        $creditGranted = Pankkitapahtuma::creditGranted($tilinro);
        $values = array( 'creditGranted' => $creditGranted,"ref" => 0);
        $this->layout->nest('content', 'luotonTiedot.index', $values);
    }
    
    public function post_index()
    {
            echo "test";
    }
    
    public function get_show($ref,$show)
    {
        $tilinro = Session::get( 'tilinro' );
        $companyTransactions = Pankkitapahtuma::companyTransactions($ref,$tilinro);
      if($companyTransactions['count'] > 0){
                
                $events = Lainantiedot::events($ref);
		$tiedot = $events['result'];
               
                if(!$show){
                    if(!empty($tiedot))
                    $koronMaara = $tiedot[0]->korko + $tiedot[0]->korkomarginaali;  
                    else
                        $koronMaara = 0;
                    $dateEvents = Pankkitapahtuma::dateEvents($companyTransactions['result'][0]->viite);
                }
      }
       $values = array('companyTransactions' => $companyTransactions, 
               'ref' => $ref,'koronMaara' => $koronMaara, 'dateEvents' => $dateEvents, 'show' => $show, 
               'tiedot' => $tiedot);
            return View::make('home.index')->nest('content', 'luotonTiedot.index', $values); 
    }       
    
    public function get_ref($ref,$show)
    {
        $tilinro = Session::get( 'tilinro' );
        $koronMaara = 0;
        $dateEvents = "";
                //$ref = Session::get('ref');
       
        if($ref){
            $arkistotunnus = $ref;
            $tilinro = Session::get( 'tilinro' );
            
            $companyTransactions = Pankkitapahtuma::companyTransactions($arkistotunnus,$tilinro);
            
            if($companyTransactions['count'] > 0){
                
                $events = Lainantiedot::events($arkistotunnus);
		        $tiedot = $events['result'];
               
                if(!$show){
                    if(!empty($tiedot))
                    $koronMaara = $tiedot[0]->korko + $tiedot[0]->korkomarginaali;  
                    else
                        $koronMaara = 0;
                    $dateEvents = Pankkitapahtuma::dateEvents($companyTransactions['result'][0]->viite);
                }
                else{
                    
                    $repayments = Lainatapahtuma::repayments($arkistotunnus);
                    $suoritusresult = $repayments['result'];
                    $suoritusrivit = $repayments['count'];
				
				// muuttujat
                    $myonnetty 	= $companyTransactions['result'][0]->date;
                    $kokosumma 	= $companyTransactions['result'][0]->summa;
                    if(!empty($tiedot))
                    {
                    $era	= $tiedot[0]->maksuera;
                    $korkop	= ( $tiedot[0]->korko + $tiedot[0]->korkomarginaali ) / 100;
                    }
                    else{
                        $era = 1;
                        $korkop = 0;
                    }
                    $kkkorkop	= $korkop / 12;
				
                    $erat 	= ceil($kokosumma/$era);
				
				// tulostetaan maksusuunnitelma
				
				// tulostetaan jo menneet erät
					/*$k = 0;
                                       
					while($suoritus = mysql_fetch_assoc($suoritusresult)){
						$yhtsumma = $suoritus['lyhennys'] + $suoritus['korko'];
				
						$k++;
						$kokosumma = $kokosumma - $suoritus['lyhennys'];
						
						// muotoilut
						$lyhennys = number_format(( $suoritus['lyhennys']), 2, '.', ' ');
						$korko = number_format(($suoritus['korko']), 2, '.', ' ');
						$yhtsumma = number_format(($yhtsumma), 2, '.', ' ');
						$jaljella = number_format(($kokosumma), 2, '.', ' ');
						
						if($suoritus['suoritettu']==0) echo "<tr class='alignRight'>";
						else echo "	<tr class='alignRight gray'>";
						echo "
										<td>$k.</td>
										<td>$suoritus[erapaiva]</td>
										<td>$lyhennys&euro;</td>
										<td>$korko&euro;</td>
										<td>$yhtsumma&euro;</td>
										<td>$jaljella&euro;</td>
									</tr>";
					}
					// Jos maksettuja eriä ei ollut
					$jaljella = $kokosumma;
					
					//testisettiä
					/*
					if(is_null($suoritus['lyhennys']))
					{
						echo 'Lyhennys "NULL"' . $lyhennys ;
					}
					
					
					if($lyhennys = '0.00')
					{
						echo 'Virheellinen lyhennys (0.00 euroa) joten laina ei lyhenny koskaan' ;
						for($i = 0; $i < 3; $i++)
						{
							$k++;
							
							// lainaa jäljellä ennen maksuerää
							$jaljellaalku = $kokosumma - ($i * $era);
							
							// kuukauden korko
							$korko 	= $jaljellaalku * $kkkorkop;
							
							// tasalyhennys
							if($tiedot->tyyppi==1){
								// lainaa jäljellä maksuerän jälkeen
								$jaljella = $jaljellaalku - $korko - $era;
								
								$yhtsumma 	= $era + $korko;
								$lyhennys 	= $era;
							}
							// kiinteä tasaerä
							if($tiedot->tyyppi==2){
								// lyhennyksen suuruus
								$lyhennys = $era - $korko;
								
								// lainaa jäljellä maksuerän jälkeen
								$jaljella = $jaljellaalku - $lyhennys;
		
								$yhtsumma 	= $era;
							}
							
							// Lainaa jäljellä vähemmän kuin maksuerä
							if($jaljella<=0){
								$yhtsumma = ($yhtsumma+$jaljella);
								$jaljella = 0;
							}
							
							$erapaiva = strtotime(date("Y-m-d", strtotime($myonnetty)) . "+$k month");
							$erapaiva = date('d.m.Y', $erapaiva);
						
							// muotoillaan arvot
							$lyhennys = number_format(($lyhennys), 2, '.', ' ');
							$korko = number_format(($korko), 2, '.', ' ');
							$yhtsumma = number_format(($yhtsumma), 2, '.', ' ');
							$jaljella = number_format(($jaljella), 2, '.', ' ');
						
						
							echo "<tr class='alignRight'>";
								echo "<td>$k.</td>";
								echo "<td>$erapaiva</td>";
								echo "<td>$lyhennys&euro;</td>";
								echo "<td>$korko&euro;</td>";
								echo "<td>$yhtsumma&euro;</td>";
								echo "<td>$jaljella&euro;</td>";
							echo "</tr>";
						}
					}
					else
					{
						for($i=0; $jaljella>0; $i++){
							$k++;
							// lainaa jäljellä ennen maksuerää
							$jaljellaalku = $kokosumma - ($i * $era);
							
							// kuukauden korko
							$korko 	= $jaljellaalku * $kkkorkop;
							
							// tasalyhennys
							if($tiedot['tyyppi']==1){
								// lainaa jäljellä maksuerän jälkeen
								$jaljella = $jaljellaalku - $korko - $era;
								
								$yhtsumma 	= $era + $korko;
								$lyhennys 	= $era;
							}
							// kiinteä tasaerä
							if($tiedot['tyyppi']==2){
								// lyhennyksen suuruus
								$lyhennys = $era - $korko;
								
								// lainaa jäljellä maksuerän jälkeen
								$jaljella = $jaljellaalku - $lyhennys;
		
								$yhtsumma 	= $era;
							}
							
							// Lainaa jäljellä vähemmän kuin maksuerä
							if($jaljella<=0){
								$yhtsumma = ($yhtsumma+$jaljella);
								$jaljella = 0;
							}
							
							$erapaiva = strtotime(date("Y-m-d", strtotime($myonnetty)) . "+$k month");
							$erapaiva = date('d.m.Y', $erapaiva);
						
							// muotoillaan arvot
							$lyhennys = number_format(($lyhennys), 2, '.', ' ');
							$korko = number_format(($korko), 2, '.', ' ');
							$yhtsumma = number_format(($yhtsumma), 2, '.', ' ');
							$jaljella = number_format(($jaljella), 2, '.', ' ');
							
							echo "<tr class='alignRight'>";
								echo "<td>$k.</td>";
								echo "<td>$erapaiva</td>";
								echo "<td>$lyhennys&euro;</td>";
								echo "<td>$korko&euro;</td>";
								echo "<td>$yhtsumma&euro;</td>";
								echo "<td>$jaljella&euro;</td>";
							echo "</tr>";
						}
					}
			/*
				echo "</table>";
				
				echo "</div>";
				echo "<p><a href='index.php?sivu=luotonTiedot&amp;ref=$viite' class='painike'>".localize('Takaisin')."</a></p>";
			}*/
                }
            }
           $values = array('companyTransactions' => $companyTransactions, 
               'ref' => $ref,'koronMaara' => $koronMaara, 'dateEvents' => $dateEvents, 'show' => $show, 
               'suoritusresult' => $suoritusresult,'kokosumma' => $kokosumma, 'era' => $era,
               'kkkorkop' => $kkkorkop, 'tiedot' => $tiedot, 'myonnetty' => $myonnetty);
            return View::make('home.index')->nest('content', 'luotonTiedot.index', $values); 
        }
       /* else
        {
            $creditGranted = Pankkitapahtuma::creditGranted($tilinro);
            $result = $creditGranted['result'];
            $num_rows = $creditGranted['count'];
            	if ( $num_rows != 0 ) {
                    	if($num_rows == 1)
			{
				// Haetaan arkistotunnus ja siirrytään luoton tietojen tarkastelu -sivulle
				$row = $result;
                               
                                //Redirect::to( "luotonTiedot/ref".$row[0]->arkistotunnus."");
                               // header("Location: luotonTiedot/ref".$row[0]->arkistotunnus."");
			}
                }
        }*/
        
        
    }
    
    public function __construct() {
        parent::__construct();
    }
}