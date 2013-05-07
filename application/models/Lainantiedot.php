<?php

class Lainantiedot extends Eloquent {
 
    public static $tables = 'TAMK_lainantiedot';
    
    public static function events($arkistotunnus)
    {
        $result = DB::table('TAMK_lainantiedot')->where( 'arkistotunnus', '=', $arkistotunnus);
        
        return array('count'=>$result->count(),'result'=> $result->get());	
    }
}
?>
