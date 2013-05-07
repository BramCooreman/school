<?php

class Lainatapahtuma extends Eloquent {
 
    public static $tables = 'TAMK_lainatapahtuma';

    public static function repayments($arkistotunnus)
    {        
      $result = DB::table('TAMK_lainatapahtuma')->select(array('*',
         DB::raw( "DATE_FORMAT(maksupvm, '%d.%m.%Y') AS erapaiva")))->where('arkistotunnus','=',$arkistotunnus)
              ->where_not_null('maksupvm')->order_by('maksupvm');
      
       return array('count'=>$result->count(),'result'=>$result->get());                                              
    }
}