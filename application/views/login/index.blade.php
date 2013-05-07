@section('content')
<?php

if (Session::has('Authenticated') && (Session::get('Authenticated') == 1)) {
    // Tulostaa sivun sisällön
    print "<h1>".Functions::localize('Tervetuloa Ainopankkiin!')."</h1>
            <div class='content padding20'>	
                            <p class='text'>".  Functions::localize('Ainopankki tarjoaa asiakkailleen kattavia ja monipuolisia pankkipalveluja. Sillä on toimintaa ympäri Suomea, yhteensä 10 konttoria suurimmissa kaupungeissa. Vuoden 2010 alussa Ainopankin palveluksessa oli yhteensä 300 henkilöä.')."</p>
                            <p class='text'>".  Functions::localize('Pankki on panostanut erityisesti verkkopalveluihin, jolloin konttoreiden toimintaa on pystytty tehostamaan. Ainopankki on erityisesti keskittynyt huolehtimaan yritysten pankkitoiminnasta, mutta asiakkaista löytyy myös yritysasiakkaiden työntekijöitä sekä omistajia. Suomessa asiakkaita on yhteensä 18 000, joista 7 500 on yritysasiakkaita. Kaikki asiakkaat ovat myös verkkopankkiasiakkaita, joka tekee Ainopankista merkittävän toimijan verkkoasioinnissa. Yrityksen tulos ennen veroja oli 28 miljoonaa euroa vuonna 2009.')."</p> 
                            <p class='text'>".  Functions::localize('Ainopankin arvoihin ja strategiaan kuuluu asiakkaiden ja toimintaympäristön taloudellisen menestyksen turvaaminen. Päätavoitteena on tarjota asiakkaiden tarvitsemia palveluja mahdollisimman kilpailukykyisesti.')."</p>
                        <p>".  Functions::localize('Ongelmatilanteissa ota yhteyttä')." <a href='mailto:ainopankki@kykylaakso.fi'>ainopankki@kykylaakso.fi</a></p> 
                    </div>
                    ";
} 
// Jos käyttäjänimi tai salasana meni väärin
else if (Session::has('Authenticated') && (Session::get('Authenticated') == 0)) {
        print '<p class="notValid">'.  Functions::localize('Kirjoitit käyttäjätunnuksen tai salasanan väärin.').' </p>';

        // Tuhotaan istunto
        //session_destroy();
        //session_start();
        $_SYSTEM['lang'] = 'fin';
        printAuthenticationForm();
	}
	// Käyttäjä kirjautuu ensimmäistä kertaa
	else {
		if(!isset($_SESSION)) 
			session_start();
		
		$lang = (string) Cookie::get('lang');
		$_SYSTEM['lang'] = $lang;
		printAuthenticationForm();
	}
	
	/**
	 * Tulostetaan kirjautumislomake
	 */
	function printAuthenticationForm() {
        ?>    <div id="login">
                <p id="tervetuloa">{{ Functions::localize('Tervetuloa Ainopankkiin!') }}</p>
                    <div id="kirjaudulomake">		
                        {{ Form::open('login','POST') }}
                            <table id="authentication">
                                <tr>
                                <td> {{ Functions::localize('Käyttäjätunnus:') }}</td>
                                <td>
                                {{ Form::text('username', '', array("size" => 20, "maxlength" => 30, "class" => "kentta")) }}
                                </td>
                                </tr>
                                <tr>
                                <td>{{ Functions::localize('Salasana:') }}</td>
                                <td> {{ Form::password('password', array("size" => 20, "maxlength" => 15, "class" => "kentta")) }}
                                </td>
                                </tr>
                                </table>
                                <p id="painikkeet">
                                {{ Form::submit(Functions::localize('KIRJAUDU'), array("class" => "painike")) }}
                                {{ Form::reset(Functions::localize('TYHJENNÄ'), array("class" => "painike")) }}
                                </p>
               {{ Form::close() }}
               </div><!-- /kirjaudu -->
            </div><!-- /login -->
        <?php
        }
        ?>
@endsection