<?php

class Pankkitili extends Eloquent {
 
    public static $tables = 'TAMK_pankkitili';
 
    public static function accountNumber($saaja) {
        $query =  DB::table('TAMK_pankkitili')->select('omistaja')
                ->where('yhtio', '=', 'pankk')->where('tilinro', '=',$saaja);      
        return array('count'=>$query->count(),'result'=>$query->get());
    }   
    
    public static function bankAccount($ytunnus)
    {
        $query =  DB::table('TAMK_pankkitili')->where('ytunnus','=',$ytunnus);
        return array('count'=>$query->count(),'result'=>$query->get());
    }
}
