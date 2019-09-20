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
        return base64_encode(preg_replace('/\s|\\\/', '',self::crypt(preg_replace('/\s|\\\/', '', $sourceString), $password)));
    }
}
