@section('content')

<h1>{{ Functions::localize('Erääntyvät maksut') }}</h1>
    <p class="yhtionTiedot"><strong><?php $yhtio ?></strong></p>
    <?php if ($bankAccount['count'] == 0) {
		echo "<p>".Functions::localize('Pankkitiliä ei löydy')."</p>";
	}
	else {
		
		 $tilinro = $bankAccount['result'][0]->tilinro;
		
		// Poistetaan erääntyvä maksu
           
		
		echo "<table class='tilinTiedot'><tr><td>".Functions::localize('Tilinro:')."</td><td> $tilinro</td></tr>";
		
        	echo "<tr><td>".Functions::localize('Erääntyvät maksut')." </td><td>"
			. $printToday ."</td></tr></table>"; 
                
                if (empty($result)) {
			echo "<div class='content padding20'>
				<p>".Functions::localize('Ei erääntyviä maksuja')."</p>

				<table id='tilioteTable'>";
                }else{
                    echo "	<div class='content'>
				<table id='tilioteTable'>
					<tr>
						<th>Tap.pvm</th>
						<th>Saajan nimi</th>
						<th>Maksajan nimi</th>
						<th>Summa</th>
						<th>Selite</th>
						<th>Poista</th>
					</tr>
				";
                    $k = 0;
                    $i= 1;
                            while($row = $result->get()) {
				$tapvm = $row[$k]->tapvm;
				$saajanNimi = $row[$k]->saajannimi;
				$maksajanNimi = $row[$k]->maksajannimi;
				$summa = $row[$k]->summa;
				$selite = $row[$k]->selite;
				$maksaja = $row[$k]->maksaja;
				$arkistotunnus = $row[$k]->arkistotunnus;
				
				echo "<tr";
					if ($i%2 == 1) echo " class='oddRow'";
					$i++;
				echo ">
						<td>".date('d.m.Y',strtotime($tapvm))."</td>
						<td>$saajanNimi</td>
						<td>$maksajanNimi</td>
					";
				
				// jos laskun maksaja on sama kuin yrityksen oma tili, on kyse maksusta (eli tulostetaan miinusmerkki)
				if ( $row[$k]->maksaja == $tilinro) {
					echo "<td>-$summa</td>";
					$tilinSaldoAikavalilla = $tilinSaldoAikavalilla - $summa;
				} else {
					echo "<td>+$summa</td>";
					$tilinSaldoAikavalilla = $tilinSaldoAikavalilla + $summa;
				}
				
				echo "<td>$selite</td>";
				
				// Erääntyvän laskun poisto
				$varmistus = Functions::localize('Oletko varma että haluat poistaa maksun?');
				echo "<td>";
                                echo Form::open('eraantyvatmaksut','POST');
				echo Form::submit('x',array('name' => 'poista', 'onclick' => 'javascript: return confirm(\'Oletko varma, että haluat poistaa tapahtuman?\');'));
				echo Form::hidden('tapahtuma',$arkistotunnus);
				echo Form::close();
					echo"	</td>
					</tr>
					";
                                $k++;
			}
		}
		echo "
			</table>";

		echo "</div>"; //div tilitapahtumat
        }
?>

 @endsection  