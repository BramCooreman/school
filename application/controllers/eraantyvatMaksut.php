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
        $payments = Pankkitapahtuma::payments($tilinro,$today);

        $values = array('yhtio'=> $yhtio, 'bankAccount' => $bankAccount, 'printToday' => $printToday, 'payments' => $payments);
       // return Response::json($values);
        return View::make('home.index')->nest('content','eraantyvatMaksut.index',$values);
                //$this->layout->nest('content', 
    }
    
    public function post_index()
    {
	if(Input::has('poista') && Input::has('tapahtuma')){
            Pankkitapahtuma::paymentDelete(Input::get('tapahtuma'), $tilinro);
        }
    }
    
    //Redirect first to authentication and then continue
    public function __construct() {
        parent::__construct();
    }
}
?>
