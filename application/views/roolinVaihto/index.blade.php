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
<h1>{{ Functions::localize('Asiakasroolin vaihto') }}</h1>
<div class="content padding20">
    <p>{{ Functions::localize('Valitse asiakasrooli:') }}</p>
    {{ Form::open('roolinvaihto','POST') }}
        <?php
        echo "<select name='asiakasrooli'>";
        // Tulostus listaan
        $result = $potentialCompanies['result'];
        foreach($result as $row) {
            echo "<option value=".$row->ytunnus;
            if($row->ytunnus == Session::get('ytunnus')){
                    echo " selected='selected'";
            }
            echo ">".$row->yhtionnimi."</option>\n";
         }

        echo "</select>";
        ?>
     {{ Form::submit( Functions::localize('VAIHDA'), array('class'=>'painike', 'name' => 'vaihdaRooli')) }}
      <p class='paddingTop'>{{ Functions::localize('Roolisi nyt:') }} <strong><?php echo Session::get('yhtionNimi'); ?></strong> </p>                       
</div>
@endsection  