@section('content')
<?php
$input = Input::all();
$tilinSaldoAikavalilla = 0;
if(empty($input))
{
    $startDate = false;
    $endDate = false;
    $errorText ='';
}
?>
<h1>{{ Functions::localize('Tilitapahtumat') }}</h1>
@if( $startDate != false and $endDate != false and empty($errorText) )
    <p class="yhtionTiedot"><strong><?php $yhtio; ?></strong></p>
    @if( $bankAccount['count'] == 0)
        <p>{{ Functions::localize('Pankkitiliä ei löydy') }}</p>
    @else
        <table class='tilinTiedot'>
            <tr>
                <td>{{Functions::localize('Tilinro:')}}</td>
                <td><?php echo $tilinro; ?></td>
            </tr>
            <tr>
                <td>{{ Functions::localize('Tilitapahtumat aikavälillä') }}
                </td><td><?php echo $startDate.' - '.$endDate; ?></td>
            </tr>
        </table>
        @if ($bankBalance['count'] == 0)
            <div id='tilitapahtumat'>
                <p>{{ Functions::localize('Tilitapahtumia ei löydy aikavälillä') }} 
                    <?php $startDate.' - '.$endDate.' !';?>
                </p>
        @else
            <div class='content'>
                <table id='tilioteTable'>
                        <tr>
                                <th>{{ Functions::localize('Tap.pvm') }}</th>
                                <th>{{ Functions::localize('Saajan nimi') }}</th>
                                <th>{{ Functions::localize('Maksajan nimi') }}</th>
                                <th>{{ Functions::localize('Summa') }}</th>
                                <th>{{ Functions::localize('Viite selite') }}</th>
                        </tr>
                        <tr>
                                <td></td>
                                <td></td>
                                <td>{{ Functions::localize('Alkusaldo') }} <?php echo $startDate; ?></td>
                                <td class='alignRight'><?php echo $tempSaldo; ?></td>
                                <td></td>
                        </tr>
                        
                        <?php
                        $i = 1;
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
                        }
                        ?>
                        <tr>
                                <td></td>
                                <td></td>
                                <td>{{ Functions::localize('Tapahtumat yhteensä') }}</td>
                                <td class='alignRight'><?php number_format($tilinSaldoAikavalilla,2,'.',' ')?></td>
                                <td></td>
                        </tr>
                        <tr>
                                <td></td>
                                <td></td>
                                <td>{{ Functions::localize('Tilin saldo') }}<? echo $endDate; ?></td>

                   
                   
                            <td class='alignRight'><?php echo $tempSaldo; ?></td>
                            <td></td>
                    </tr>
                    </table>
         @endif
            <table class='tilinSaldot'>
                <tr>
                    <td>{{ Functions::localize('Tilin saldo') }} <?php echo date('d.m.Y').":";?>
                    </td>
                    <td>
                        @if($tilinSaldo >= 0)
                            <?php echo "+"; ?>
                        @endif
                        <?php echo $tilinSaldo;?> {{ Functions::localize('euroa') }}
                    </td>
                </tr>
            </table>
         </div><!-- /tilitapahtumat -->
    <p>{{ HTML::link('tilitapahtumat', Functions::localize('Takaisin'), array('class' => 'painike'))}}</p>
  @endif
@else
<p class="errorMessage"> <?php echo $errorText ?></p>
   <div id="login" class="content">
        <h2>{{ Functions::localize('Hae aikaväliltä') }}</h2>
                <div id="kirjaudulomake">
                    {{ Form::open('tilitapahtumat','POST') }}
                                <p>{{ Functions::localize('Syötä tiedot muodossa pp.kk.vvvv') }}</p>
                                <table id="authentication">
                                <tr>
                                <td>{{ Functions::localize('alkupvm') }}</td>
                                <td>
                                {{ Form::text('startdate', $startDate, array('id' => 'startdate')) }}
                                <script type="text/javascript">
                                        calendar.set("startdate");
                                </script>
                                </td>
                                </tr>
                                <tr>
                                <td>{{ Functions::localize('loppupvm') }}</td>
                                <td>
                                {{ Form::text('enddate', $endDate, array('id' => 'enddate')) }}
                                <script type="text/javascript">
                                        calendar.set("enddate");
                                </script>
                                </td>
                                </tr>
                                </table>
                                <p id="painikkeet">
                                 {{ Form::submit( Functions::localize('HAE'), array('class'=>'painike', 'name' => 'search')) }}
                                 {{ Form::reset( Functions::localize('TYHJENNÄ'), array('class'=>'painike')) }}
                                </p>
                    {{ Form::close() }}
                </div><!-- /kirjaudu -->
    </div><!-- /login -->
  @if($bankAccount['count'] == 0)
        <p>{{ Functions::localize('Pankkitiliä ei löydy') }}</p>
   @endif
   
   @if($organizationInfo['count']== 0)
        <p>{{ Functions::localize('Ei tilitapahtumia') }}</p>		
   @else
        <div id="tilitapahtumat" class="viimeisetTilit">
		<h2>{{ Functions::localize("Viimeisimmät tilitapahtumat") }}</h2>
                    <table id="tilioteTable" class="tiliTiedot">
                        <tr>
                                <th>{{ Functions::localize("Tap.pvm") }}</th>
                                <th>{{ Functions::localize("Saajan nimi") }}</th>
                                <th>{{ Functions::localize("Maksajan nimi") }}</th>
                                <th>{{ Functions::localize("Summa") }}</th>
                                <th>{{ Functions::localize("Viite selite") }}</th>
                        </tr>
                       	
				<?php	$i = 1;
					 foreach($organizationInfo['result'] as $row){
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
					}
						
			echo '
				</table>
				</div><!-- /tilitapahtumat -->';?>
                        @endif   
  @endif
 @endsection  