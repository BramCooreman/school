<?php

class EraantyvatMaksut_Controller extends Base_Controller
{    
    public $restful = true;
    
    public function get_index()
    {
        Session::put('sivu','eraantyvatMaksut');
        $today = date('Y-m-d');
        $yhtio = Session::get('yhtionNimi');
        $ytunnus = Session::get( 'ytunnus' );
	
        $bankAccount = Pankkitili::bankAccount($ytunnus);
        $tilinro = $bankAccount['result'][0]->tilinro;
	$printToday = date('d.m.Y',strtotime($today));
        $result = Pankkitapahtuma::payments($tilinro,$today);

        $values = array('yhtio'=> $yhtio, 'bankAccount' => $bankAccount, 'printToday' => $printToday, 'result' => $result);
     
        $this->layout->nest('content', 'eraantyvatMaksut.index', $values);
    }
    
    public function post_index()
    {
              echo 'test';
		if(isset($_POST['poista']) && isset($_POST['tapahtuma'] )){
			$delete = "	DELETE FROM TAMK_pankkitapahtuma
						WHERE arkistotunnus = '$_POST[tapahtuma]'
						AND maksaja = '$tilinro'
						LIMIT 1";
			mysql_query($delete);
		}
    }
}
?>
