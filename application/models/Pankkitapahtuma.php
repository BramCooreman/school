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
    
}