<?php

class Pankkitapahtuma extends Eloquent {
 
    public static $tables = 'TAMK_pankkitapahtuma';
 
    public static function payments($tilinro,$today) {
        $result = DB::table('TAMK_pankkitapahtuma')->select(array(
                                'tapvm'
                            , 'saajanNimi'
                            , 'maksajanNimi'
                            , 'summa'
                            , 'selite'
                            , 'maksaja'
                            , 'arkistotunnus'
        ))->where('maksaja', '=', $tilinro)->where('tapvm','>',$today)
                ->where(function($query)
                {
                    $query->or_where('eiVaikutaSaldoon','=','');
                    $query->or_where_null('eiVaikutaSaldoon');
                    $query->or_where('eiVaikutaSaldoon','=','k');
                    $query->or_where('eiVaikutaSaldoon','=','l');
                    $query->or_where('eiVaikutaSaldoon','=','a');
                    $query->or_where('eiVaikutaSaldoon','=','m');
                });
             
         return array('count'=>$result->count(),'result'=>$result->get());
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
    
       public static function companyTransactions($arkistotunnus, $tilinro) {
         
	$result = DB::table('TAMK_pankkitapahtuma')->select(array('*'
                                        , DB::RAW("DATE_FORMAT(tapvm, '%d.%m.%Y') AS date")))->
                where('arkistotunnus', '=', $arkistotunnus)
                ->where('saaja' ,'=' ,$tilinro);
        
        return array('count'=>$result->count(),'result'=>$result->get());
    }
    
    public static function dateEvents($viite)
    {
        $query = DB::query("SELECT *, DATE_FORMAT(tapvm, '%d.%m.%Y') AS date, IF(eiVaikutaSaldoon = 'l', summa, summa*-1) AS summa
                                    , IF(selite REGEXP '[0-9]{2}\/[0-9]{4}$', SUBSTRING(selite, -7), 0) AS eranro
                                    FROM TAMK_pankkitapahtuma
                                    WHERE viite = '".$viite."'
                                    ORDER BY tapvm ASC, eranro ASC, eiVaikutaSaldoon ASC");
	 return $query;
    }
    
     public static function creditGranted($tilinro)
    {
        $result = DB::table('TAMK_pankkitapahtuma')->select(array(
            '*',
           DB::raw("DATE_FORMAT(tapvm, '%d.%m.%Y') AS date"),
            DB::raw('SUM(summa) AS summa')
        ))->where('eiVaikutaSaldoon', '=', 'l')->where('yhtio', '=', 'pankk' )
                ->where('saaja', '=',$tilinro)->group_by('viite')->order_by('tapvm', 'asc');
        
      return array('count'=>$result->count(),'result'=>$result->get());
    }
    
    public static function paymentDelete($tapahtuma, $tilinro)
    {
        $result = DB::table("TAMK_pankkitapahtuma")->where('arkistotunnus', '=', $tapahtuma )
                ->where('maksaja', '=', $tilinro)->take(1)->delete();
    }
    
}