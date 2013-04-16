@section('content')
<?php
$input = Input::all();
if(empty($input))
{
    $maksunErapaiva = '';
    $errorText= '';
    $saaja= '';
    $saajanNimi= '';
    $summa= '';
    $viite= '';
    $viesti= '';
}
?>
<h1> {{ Functions::localize('Uusi maksu') }}</h1>
<?php
if (Input::has('jatka') && !$errorText) {
?>

    <div id='uusiMaksuLomake' class='content padding20'>
	<p id='uusiMaksu'>{{ Functions::localize('Hyväksy maksu') }}</p>
			
		<table id='hyvaksyTable'>
                    {{ Functions::getPossibleTableRow(Functions::localize('Maksajan tili'), $maksaja) }}
                    {{ Functions::getPossibleTableRow(Functions::localize('Maksajan nimi'), $maksajanNimi, false, true) }}
                    {{ Functions::getPossibleTableRow(Functions::localize('Saajan tilinumero'), $saaja, true) }}		
                    {{ Functions::getPossibleTableRow(Functions::localize('Saajan nimi'), $saajanNimi, false, true) }}
                    {{ Functions::getPossibleTableRow(Functions::localize('Eräpäivä'), $maksunErapaiva) }}
                    {{ Functions::getPossibleTableRow(Functions::localize('Maksun määrä'), number_format($summa, 2, ',', ' '), true) }}
                    {{ Functions::getPossibleTableRow(Functions::localize('Viite'), $viite) }}
                    {{ Functions::getPossibleTableRow(Functions::localize('Viesti'), $viesti) }}
		</table>
		
                {{ Form::open('uusimaksu','POST') }}
                        {{ Functions::getPossibleHiddenField($maksupvm, "maksupvm") }}
			{{ Functions::getPossibleHiddenField($maksunErapaiva, "maksunErapaiva") }}
			{{ Functions::getPossibleHiddenField($maksajanNimi, "maksajanNimi") }}
			{{ Functions::getPossibleHiddenField($saajanNimi, "saajanNimi") }}
			{{ Functions::getPossibleHiddenField($maksaja, "maksajanTili") }}
			{{ Functions::getPossibleHiddenField($saaja, "saajanTili") }}
			{{ Functions::getPossibleHiddenField($viite, "viite") }}
			{{ Functions::getPossibleHiddenField($viesti, "viesti") }}
			{{ Functions::getPossibleHiddenField($summa, "summa") }}
			
			<p id='painikkeet'>
                            {{ Form::submit( Functions::localize('MUUTA TIETOJA'), array('name' => 'muuta', 'class' => 'painike')) }}
			    {{ Form::submit( Functions::localize('HYVÄKSY'), array('name' => 'hyvaksyMaksu', 'class' => 'painike')) }}
                         </p>
		{{ Form::close() }}
    </div><!-- uusimaksulomake -->
?><?php
}
else
{
   if(!$maksunErapaiva) 
        $maksunErapaiva = date('d.m.Y');
?>

 <div id="uusiMaksuLomake" class="content padding20">
    <p> * {{Functions::localize('pakollinen kenttä') }} <br/>
       ** {{ Functions::localize('toinen kenttä pakollinen') }}<br/><br/></p>
        <p class="errorMessage"> <?php echo $errorText ?></p>
        
        {{ Form::open('uusimaksu','POST') }}
        <table id="uusiMaksuKentat">
            <tr>
                <td>{{ Functions::localize('Maksetaan tililtä') }}</td>
                <td>{{ Session::get('tilinro') }}
                {{ Form::hidden('maksajanTili', Session::get('tilinro'), array('onkeypress'=>'return disableEnterKey(event)')) }}
                </td>
            </tr>
                <tr>
                <td>{{ Functions::localize('Maksajan nimi') }}</td>
                <td>{{ Session::get('yhtionNimi') }}
                {{ Form::hidden('maksajanNimi', Session::get('yhtionNimi'), array('onkeypress'=>'return disableEnterKey(event)')) }}
                </td>
             </tr>
             <tr>
                <td>&nbsp;</td>
             </tr>
             <tr>
                <td>{{ Functions::localize('Saajan tilinumero') }}*</td>
                <td>
                {{ Form::text('saajanTili', $saaja, array('size'=> "20", 'maxlength' => "22", 'class'=>"kentta", 'onkeypress'=>"return disableEnterKey(event)")) }}
                </td>
                </tr>
                <tr>
                <td>{{ Functions::localize('Saajan nimi') }} *</td>
                <td>
                    {{ Form::text('saajanNimi', $saajanNimi, array('size'=> "20", 'maxlength' => "22", 'class'=>"kentta", 'onkeypress'=>"return disableEnterKey(event)")) }}
                </td>
                </tr>
                <tr>
                <td>{{ Functions::localize('Eräpäivä') }} *</td>
                <td>
                {{ Form::text('maksunErapaiva', $maksunErapaiva, array('maxlength' => "10",'size'=> "10", 'id' => 'date', 'class'=>"pvmKentta", 'onkeypress'=>"return disableEnterKey(event)")) }}
                <script type="text/javascript">
                        calendar.set("date");
                </script>
                </td>
                </tr>
                <tr>
                <td>{{ Functions::localize('Maksun määrä') }} *</td>
                <td>
                  {{ Form::text('summa', $summa, array('size'=> "10", 'maxlength'=>"19", 'class'=>"kentta", 'id'=>"maksunMaaraKentta", 'onkeypress'=>"return disableEnterKey(event)")) }}EUR</td>
                </tr>
                <tr>
                <td>{{ Functions::localize('Viite') }} **</td>
                <td>
                    {{ Form::text('viite', $viite, array('size'=> "20", 'maxlength'=>"20", 'class'=>"kentta", 'onkeypress'=>"return disableEnterKey(event)")) }}
                </td>
                </tr>
                <tr>
                <td>{{ Functions::localize('Viesti') }} **</td>
                <td>
                    {{ Form::textarea('viesti',$viesti, array('class'=>'kentta','rows'=>'3','cols'=>'1')) }}
                </td>
                </tr>
                <tr>
                <td>{{ Functions::localize('Tilioteteksti') }}</td>
                <td>{{ Functions::localize('tilisiirto') }}</td>
                </tr>
        </table>
         <p id="painikkeet">
             {{ Form::submit( Functions::localize('TYHJENNÄ'), array('class'=>'painike', 'name' => 'tyhjenna')) }}
             {{ Form::submit( Functions::localize('JATKA'), array('class'=>'painike', 'name' => 'jatka')) }}
         </p>
     {{ Form::close() }}
     </div><!-- uusiMaksuLomake -->
     
<?php
}
?> 
 @endsection  