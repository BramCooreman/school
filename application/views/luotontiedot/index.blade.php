@section('content')
<?php
$jaljella = 0;
$yhtsumma = 0;
//$input = Input::all();
/*
*/
//$ref = Input::get('ref');
?>
<h1>{{ Functions::localize('Siirrä rahaa') }}</h1>

@if($ref)
    @if($companyTransactions['count'] > 0)
        @if(!$show)
            <div class='content padding20'>
                <p>
                    <span class='label'>{{ Functions::localize('Lainatili:') }}</span> 
                    <span class='value'><?php echo $companyTransactions['result'][0]->maksaja; ?></span>
                </p>
                <p>
                    <span class='label'>{{ Functions::localize('Viite:') }}</span> 
                    <span class='value'><?php echo $companyTransactions['result'][0]->viite; ?></span>
                </p>

                <table class='luotontiedot'>
                        <tr class='bold'>
                                <td>{{ Functions::localize('Pvm') }}</td>
                                <td>{{ Functions::localize('Summa') }}</td>
                                <td>{{ Functions::localize('Korko') }}</td>
                        </tr>
                        <tr>
                                <td><?php echo $companyTransactions['result'][0]->date; ?></td>
                                <td><?php echo $companyTransactions['result'][0]->summa; ?></td>
                                <td><?php echo $koronMaara.'%';?></td>
                        </tr>
                </table>

                <p>
                    <?php //echo '<a href='.URL::to_action('luotontiedot.show', array("show" => 1)).' class ="painike">'.Functions::localize('Takaisinmaksusuunnitelma').'</a>';
                    ?>

                    <?php  
                        echo HTML::link_to_action('luotonTiedot@ref',Functions::localize('Takaisinmaksusuunnitelma'), array('ref' => $ref, 'show' => 1), array("class" => "painike"));  
                    ?>
                </p>
            </div>
            <div class='content marginTop padding20'>
                <table class='luotontiedot alignLeft'>
                    <tr>
                        <th class='selite'>{{ Functions::localize('Selite') }}</th>
                        <th>{{ Functions::localize('Pvm') }}</th>
                        <th class='alignRight'>{{ Functions::localize('Summa') }}</th>
                    </tr>
                    
                    <?php $loppusumma = 0;	
      
                   foreach($dateEvents as $lyhennys) {
                        echo "	<tr>";
                        if(!empty($lyhennys->selite)){
                            echo "<td>".ucfirst($lyhennys->selite)."</td>";
                        }
                        else{
                            echo "<td>".Functions::localize('Lainan lyhennys')."</td>";
                        }

                          // Lisätään etumerkki
                        if($lyhennys->summa > 0){
                                $etumerkki = "+";
                        }
                        else{
                                $etumerkki = null;
                        }

                        echo "
                                <td>$lyhennys->date</td>
                                <td class='alignRight'>$etumerkki$lyhennys->summa</td>
                        </tr>";

                        // Vähennetään lyhennykset lainan määrästä
                        $maksutyyppi = $lyhennys->eivaikutasaldoon;
                        if($maksutyyppi != 'k' && $maksutyyppi != 'm'){
                                $loppusumma = $loppusumma+$lyhennys->summa;
                        }
                   }
             
                    $loppusumma = number_format($loppusumma, 2, '.', '');
                    if($loppusumma >= 0){
                            $loppusumma = "+".$loppusumma;
                    }

                    echo "<tr class='height30 verticalBottom borderTop bold'>
                                <td>".Functions::localize('Lainaa jäljellä')."</td>
                                <td>".date('d.m.Y')."</td>
                                <td class='alignRight'>$loppusumma</td>
                        </tr>
                </table>
            </div>
            <p>";
                echo HTML::link_to_action('luotonTiedot@index',Functions::localize('Takaisin'), array('ref' => $ref), array("class" => "painike"));  
                echo "</p>";
            ?>
            
        @else
            <div class='content marginTop padding20'>
                <table class='luotontiedot'>
                    <tr class='alignRight'>
                        <th>{{ Functions::localize('Maksuerä') }}</th>
                        <th>{{ Functions::localize('Eräpäivä') }}</th>
                        <th>{{ Functions::localize('Lyhennys') }}</th>
                        <th>{{ Functions::localize('Korko') }}</th>
                        <th>{{ Functions::localize('Maksuerä') }}</th>
                        <th>{{ Functions::localize('Lainaa jäljellä') }}</th>
                    </tr>
                        <?php
                        $k = 0;
                            foreach($suoritusresult as $suoritus){
                                $yhtsumma = $suoritus->lyhennys + $suoritus->korko;

                                        $k++;
                                        $kokosumma = $kokosumma - $suoritus->lyhennys;

                                        // muotoilut
                                        $lyhennys = number_format(( $suoritus->lyhennys), 2, '.', ' ');
                                        $korko = number_format(($suoritus->korko), 2, '.', ' ');
                                        $yhtsumma = number_format(($yhtsumma), 2, '.', ' ');
                                        $jaljella = number_format(($kokosumma), 2, '.', ' ');

                                        if($suoritus->suoritettu==0) echo "<tr class='alignRight'>";
                                        else echo "	<tr class='alignRight gray'>";
                                        echo "
                                                <td>$k.</td>
                                                <td>$suoritus->erapaiva</td>
                                                <td>$lyhennys&euro;</td>
                                                <td>$korko&euro;</td>
                                                <td>$yhtsumma&euro;</td>
                                                <td>$jaljella&euro;</td>
                                        </tr>";
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
                                                    if(!empty($tiedot))
                                                    {
                                                        if($tiedot[0]->tyyppi==1){
                                                            // lainaa jäljellä maksuerän jälkeen
                                                            $jaljella = $jaljellaalku - $korko - $era;

                                                            $yhtsumma 	= $era + $korko;
                                                            $lyhennys 	= $era;
                                                        }
                                                        // kiinteä tasaerä
                                                        if($tiedot[0]->tyyppi==2){
                                                                // lyhennyksen suuruus
                                                                $lyhennys = $era - $korko;

                                                                // lainaa jäljellä maksuerän jälkeen
                                                                $jaljella = $jaljellaalku - $lyhennys;

                                                                $yhtsumma 	= $era;
                                                        }
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
                                                    //$jaljella = number_format(($jaljella), 2, '.', ' ');

                                                    echo "<tr class='alignRight'>";
                                                            echo "<td>$k.</td>";
                                                            echo "<td>$erapaiva</td>";
                                                            echo "<td>$lyhennys&euro;</td>";
                                                            echo "<td>$korko&euro;</td>";
                                                            echo "<td>$yhtsumma&euro;</td>";
                                                            echo "<td>$jaljella&euro;</td>";
                                                    echo "</tr>";
                                            }
                                    }else
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
					}?>
          
                </table>
            </div>
            <p><?php echo "<a href=".URL::to_action('luotontiedot.index', array("ref" => $ref ))." class='painike'>".Functions::localize('Takaisin')."</a>";?>        
        @endif
    @else
        <p>{{ Functions::localize('Sinulla ei ole riittäviä oikeuksia tämän luoton tietojen tarkasteluun.') }}</p>
    @endif	
	
@else
    @if ( $creditGranted['count'] != 0 ) 
          @if( $creditGranted['count'] == 1)
          @endif
                
        <div class='content padding20'>
            <p class='bold'>{{ Functions::localize('Myönnetyt luotot') }}</p>
                <table class='luotontiedot'>
                    <tr>
                            <th>{{ Functions::localize('Pvm') }}</th>
                            <th>{{ Functions::localize('Summa') }}</th>
                            <th>{{ Functions::localize('Lainatili') }}</th>
                            <th>{{ Functions::localize('Viite') }}</th>	
                    </tr>
       <?php foreach( $creditGranted['result'] as $row){
                echo   "<tr>	
                           <td>". $row->date . "</td>
                           <td>". $row->summa. "&euro;</td>
                           <td>". $row->maksaja . "</td>
                           <td>";

                echo HTML::link_to_action('luotonTiedot@show',$row->viite, array('ref' => $row->arkistotunnus, 'show'=> 0)); 
                echo "</td>";
             }?>

			
                        </tr>
                </table>
        </div>
    @else
             <p> {{ Functions::localize('Ei myönnettyjä luottoja.') }}</p>
    @endif

@endif  

@endsection  