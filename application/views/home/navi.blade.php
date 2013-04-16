@section('left')
<?php 

print '<ul id="navi">';		
        if (Session::has('Authenticated') && (Session::get('Authenticated') == 1)) {
                        echo '<li>'.HTML::link('home', Functions::localize('Etusivu'), array('class'=>'etusivu'))."</li>
                        <li>".HTML::link('uusimaksu',  Functions::localize('Uusi maksu'),array('class' => "uusiMaksu")).'</a></li>
                        <li>'.HTML::link('eraantyvatmaksut',  Functions::localize('Erääntyvät maksut'),array('class' => "eraantyvatMaksut")).'</a></li>
                        <li><a href="index.php?sivu=tilitapahtumat" class="tilitapahtumat" >'.  Functions::localize('Tilitapahtumat').'</a></li>
                        <li><a href="index.php?sivu=luotonTiedot" class="luotonTiedot">'.Functions::localize('Luoton tiedot').'</a></li>';

                        if(Session::get('kayttaja') == 'superuser')
                        {
                                echo '<li><a href="index.php?sivu=siirraRahaa" class="siirraRahaa">'.Functions::localize('Siirrä rahaa').'</a></li>';
                        }
                print '<li><a href="index.php?sivu=roolinVaihto" class="roolinVaihto">'.Functions::localize('Asiakasroolin vaihto').'</a></li>';
                print '<li><a href=logout>'.Functions::localize('Kirjaudu ulos').'</a></li>';
        }
	
	print 		'</ul><!-- /navi -->';
				 echo HTML::image("images/navilogo.png","Ainopankki - ainoa pankkisi", array("id" => "naviLogo"));
			print'<!-- /left -->
			';
?>
@endsection