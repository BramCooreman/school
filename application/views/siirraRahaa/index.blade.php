@section('content')
<?php
$input = Input::all();
if(empty($input))
{
    $errorText= '';
    $saaja= '';
    $saajanNimi= '';
    $summa= '';
    $viite= '';
    $viesti= '';
}
?>
<h1>{{ Functions::localize('Siirrä rahaa') }}</h1>
<div class="content padding20">
@if(Input::has('jatka') && !$errorText)
 <div id='uusiMaksuLomake' class='content padding20'>
        <p id='uusiMaksu'>{{ Functions::localize('Hyväksy maksu') }}</p>
		
	<table id='hyvaksyTable'>
		{{ Functions::getPossibleTableRow(Functions::localize('Saajan tilinumero'), $saaja, true) }}	
		{{ Functions::getPossibleTableRow(Functions::localize('Saajan nimi'), $saajanNimi, false, true) }}
		{{ Functions::getPossibleTableRow(Functions::localize('Eräpäivä'), $maksunErapaiva) }}
		{{ Functions::getPossibleTableRow(Functions::localize('Maksun määrä'), number_format($summa, 2, ',', ' '), true) }}
		{{ Functions::getPossibleTableRow(Functions::localize('Viesti'), $viesti) }}
	</table>
	
        {{ Form::open('siirrarahaa','POST') }}
		{{ Functions::getPossibleHiddenField($maksunErapaiva, "maksunErapaiva") }}
		{{ Functions::getPossibleHiddenField($saajanNimi, "saajanNimi") }}
		{{ Functions::getPossibleHiddenField($saaja, "saajanTili") }}
		{{ Functions::getPossibleHiddenField($summa, "summa") }}
		{{ Functions::getPossibleHiddenField($viesti, "viesti") }}
		
		<p id='painikkeet'>
                     {{ Form::submit( Functions::localize('MUUTA TIETOJA'), array('class'=>'painike', 'name' => 'muuta')) }}
                     {{ Form::submit( Functions::localize('HYVÄKSY'), array('class'=>'painike', 'name' => 'hyvaksySiirto')) }}
                </p>
        {{ Form::close() }}
    </div><!-- uusimaksulomake -->
@else
<div id="uusiMaksuLomake" class="content padding20">
    <p> * {{ Functions::localize('pakollinen kenttä') }}</p>
    <p class="errorMessage"><?php echo $errorText; ?></p>
        {{ Form::open('siirrarahaa','POST') }}
            <table id="uusiMaksuKentat">
                    <tr>
                    <td>{{ Functions::localize('Saajan tilinumero') }} *</td>
                    <td>
                        {{ Form::text('saajanTili', $saaja, array('size' => '20', 'maxlength' => '22', 'class' => 'kentta', 'onkeypress' => 'return disableEnterKey(event)')) }}
                    </td>
                    </tr>
                    <tr>
                    <td>{{ Functions::localize('Saajan nimi') }} *</td>
                    <td>
                        {{ Form::text('saajanNimi', $saajanNimi, array('size' => '20', 'maxlength' => '35', 'class' => 'kentta', 'onkeypress' => 'return disableEnterKey(event)')) }}
                    </td>
                    </tr>
                    <tr>
                    <td>{{ Functions::localize('Maksun määrä') }} *</td>
                    <td>
                        {{ Form::text('summa', $summa, array('size' => '10', 'maxlength' => '19', 'class' => 'kentta', 'id' => 'maksunMaaraKentta', 'onkeypress' => 'return disableEnterKey(event)')) }}
                    </tr>
                    <tr>
                    <td>{{ Functions::localize('Tilioteteksti') }}</td>
                    <td>{{ Functions::localize('tilisiirto') }}</td>
                    </tr>
                    <tr>
                    <td>{{ Functions::localize('Viesti') }}</td>
                    <td>{{ Functions::localize("Ylläpitäjän suorittama rahan siirto") }}</td>
                    </tr>
            </table>

                    <p id="painikkeet">
                         {{ Form::submit( Functions::localize('TYHJENNÄ'), array('class'=>'painike', 'name' => 'tyhjenna')) }}
                         {{ Form::submit( Functions::localize('JATKA'), array('class'=>'painike', 'name' => 'jatka')) }}
                    </p>
        {{ Form::close() }}
</div>
@endif
</div>
@endsection  