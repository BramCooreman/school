@section('left')
<?php 
print '<ul id="navi">';		
    if (Session::has('Authenticated') && (Session::get('Authenticated') == 1)) {
        echo '<li>'.HTML::link('home', Functions::localize('Etusivu'), array('class'=>'etusivu'))."</li>
        <li>".HTML::link('uusimaksu',  Functions::localize('Uusi maksu'),array('class' => "uusiMaksu")).'</li>
        <li>'.HTML::link('eraantyvatmaksut',  Functions::localize('Erääntyvät maksut'),array('class' => "eraantyvatMaksut")).'</li>
        <li>'.HTML::link('tilitapahtumat',  Functions::localize('Tilitapahtumat'),array('class' => "tilitapahtumat")).'</li>
        <li>'.HTML::link('luotontiedot',  Functions::localize('Luoton tiedot'),array('class' => "luotonTiedot")).'</li>';

        if(Session::get('kayttaja') == 'superuser')
        {
                echo '<li>'.HTML::link('siirrarahaa',  Functions::localize('Siirrä rahaa'),array('class' => "siirraRahaa")).'</li>';
        }
        print '<li>'.HTML::link('roolinvaihto',  Functions::localize('Asiakasroolin vaihto'),array('class' => "roolinVaihto")).'</li>';
        print '<li>'.HTML::link('logout',  Functions::localize('Kirjaudu ulos')).'</li>';
    }
	print '</ul><!-- /navi -->';
        echo HTML::image("images/navilogo.png","Ainopankki - ainoa pankkisi", array("id" => "naviLogo"));
        print'<!-- /left -->';
?>
@endsection