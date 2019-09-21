<?php
namespace App\Service;

/**
 * 加密
 */
class Encrypt extends Service{

    /**
     * @param sourceString
     * @param password
     * @return 密文
     */
    public static function encrypt($sourceString, $password) {
        return self::crypt($sourceString, $password);
    }

    /**
     *
     * @param sourceString
     * @param password
     * @return 明文
     */
    public static function decrypt($sourceString, $password) {
        return self::crypt($sourceString, $password, 1);
    }
    
    protected static function crypt($sourceString, $password, $t = 0){
        $p = str_split($password);
        $n = count($p);
        preg_match_all("/[\s\S]/u", $sourceString, $arr2);       
        $c = $arr2[0];
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            if ($t) {
                $mima = mb_ord($c[$i]) - mb_ord($p[$i%$n]);
            } else {
                $mima = mb_ord($c[$i]) + mb_ord($p[$i%$n]);
            }
            $c[$i] = mb_chr($mima);
        }
        return implode('', $c);
    }

    /**
     * @param sourceString
     * @param password
     * @return 密文
     */
    public static function encrypt2($sourceString, $password) {
        $p = str_split($password);
        $n = count($p);
		$sourceString = preg_replace("/\s/", "", $sourceString);
        preg_match_all("/[\s\S]/u", $sourceString, $arr2);
        $c = $arr2[0];
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            $mima = mb_ord($c[$i]) + mb_ord($p[$i%$n]);
            $c[$i] = mb_chr(self::parseCode($mima));
        }
        return implode('', $c);
    }
	
    protected static function parseCode($code) {
		$b = 65;
        $d = $code % $b + $b;
    	if($d > 90 && $d < 97){
    		$d = $d - 42;
    	} else if($d > 122){
    		$d = $d - 72;
    	}
        return $d;
    }
}
