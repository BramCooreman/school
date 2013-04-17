<?php

class Pankkitapahtuma extends Eloquent {
 
    public static $table = 'TAMK_pankkitapahtuma';
 
    public static function payments($tilinro,$today) {
        $query =  DB::query('SELECT    tapvm
                            , saajanNimi
                            , maksajanNimi
                            , summa
                            , selite
                            , maksaja
                            , arkistotunnus
                    FROM    TAMK_pankkitapahtuma
                    WHERE    (maksaja = ?) 
                    AND        (tapvm > ?) 
                    AND        (eiVaikutaSaldoon = ""
                                OR eiVaikutaSaldoon IS NULL
                                OR eiVaikutaSaldoon = "l"
                                OR eiVaikutaSaldoon = "a"
                                OR eiVaikutaSaldoon = "k"
                                OR eiVaikutaSaldoon = "m"
                            )', array($tilinro,$today));      
        return $query;
    }
    
    public static function bankBalance($tilinro,$startDateMySql,$endDateMySql) {
        $result = DB::table('TAMK_pankkitapahtuma')->select(array('tapvm'
                                        , 'saajanNimi'
                                        , 'maksajanNimi'
                                        , 'summa'
                                        , 'selite'
                                        , 'viite'
                                        , 'maksaja'))->
                where(function($query) use ($tilinro)
                {
                    $query->or_where('saaja','=',$tilinro);
                    $query->or_where('maksaja','=',$tilinro);
                })->where(function($query) use ($startDateMySql,$endDateMySql)
                {
                    $query->where('tapvm','>=',$startDateMySql);
                    $query->where('tapvm','<=',$endDateMySql);
                })->where(function($query)
                {
                    $query->or_where('eiVaikutaSaldoon','=','');
                    $query->or_where_null('eiVaikutaSaldoon');
                    $query->or_where('eiVaikutaSaldoon','=','k');
                    $query->or_where('eiVaikutaSaldoon','=','l');
                    $query->or_where('eiVaikutaSaldoon','=','a');
                    $query->or_where('eiVaikutaSaldoon','=','m');
                })->order_by('tapvm','asc');
        
       return array('count'=>$result->count(),'result'=>$result->get());
    }
    
       public static function organizationInfo($tilinro) {
         
	$result = DB::table('TAMK_pankkitapahtuma')->select(array('tapvm'
                                        , 'saajanNimi'
                                        , 'maksajanNimi'
                                        , 'summa'
                                        , 'selite'
                                        , 'viite'
                                        , 'maksaja'))->
                where(function($query) use ($tilinro)
                {
                    $query->or_where('saaja','=',$tilinro);
                    $query->or_where('maksaja','=',$tilinro);
                })->where('tapvm','<=' ,'now()')
                ->where(function($query)
                {
                    $query->or_where('eiVaikutaSaldoon','=','');
                    $query->or_where_null('eiVaikutaSaldoon');
                    $query->or_where('eiVaikutaSaldoon','=','k');
                    $query->or_where('eiVaikutaSaldoon','=','l');
                    $query->or_where('eiVaikutaSaldoon','=','a');
                    $query->or_where('eiVaikutaSaldoon','=','m');
                })->order_by('tapvm','desc')->take(5);
        
        return array('count'=>$result->count(),'result'=>$result->get());
    }
}