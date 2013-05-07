<?php

class Kuka extends Eloquent {
    
    public static $tables = 'kuka';
    
    public static function potentialCompanies($user)
    {
        $result = DB::table('kuka')->select(array(
            DB::raw("kuka.yhtio AS 'yhtio'"),
            DB::raw("yhtio.nimi AS 'yhtionNimi'"),
            DB::raw("yhtio.ytunnus AS 'ytunnus'")
            
        ))->join('yhtio', function($join){
            $join->on('kuka.yhtio','=','yhtio.yhtio' );
        })->where( 'kuka', '=', $user)->order_by('yhtio.nimi','asc');
                    
        
        return array('count'=>$result->count(),'result'=> $result->get());	
    }
    
    public static function changeRole($user,$role)
    {
        $result = DB::table('kuka')->select(array(
            DB::raw("kuka.kuka AS 'kuka' "),
            DB::raw("kuka.yhtio AS 'yhtio'"),
            DB::raw("yhtio.nimi AS 'yhtionNimi'"),
            DB::raw("TAMK_pankkitili.tilinro AS 'tilinro'"),
            DB::raw("yhtio.ytunnus AS 'ytunnus'")
            
        ))->join('yhtio', function($join){
            $join->on('kuka.yhtio','=','yhtio.yhtio' );
        })->join('TAMK_pankkitili', function($join){
            $join->on('yhtio.ytunnus', '=', 'TAMK_pankkitili.ytunnus' );
        })->where( 'kuka.kuka', '=', $user)->where('yhtio.ytunnus', '=', $role);
       
        return array('count'=>$result->count(),'result'=> $result->get());
    }
}
?>
