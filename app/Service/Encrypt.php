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
        $sourceString = base64_encode($sourceString);
        $p = str_split($password);
        $n = count($p);
        $c = str_split($sourceString);
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            $mima = mb_ord($c[$i]) + mb_ord($p[$i%$n]);
            $c[$i] = mb_chr($mima);
        }
        return base64_encode(implode('', $c));
    }

    /**
     *
     * @param sourceString
     * @param password
     * @return 明文
     */
    public static function decrypt($sourceString, $password) {
        $sourceString = base64_decode($sourceString);
        $p = str_split($password);
        $n = count($p);
        preg_match_all("/./u", $sourceString, $arr2);       
        $c = $arr2[0];
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            $mima = mb_ord($c[$i]) - mb_ord($p[$i%$n]);
            $c[$i] = mb_chr($mima);
        }
        return base64_decode(implode('', $c));
    }
    
    
    /**
     * @param sourceString
     * @param password
     * @return 密文
     */
    public static function encrypt2($sourceString, $password) {
        $p = str_split($password);
        $n = count($p);
        preg_match_all("/./u", $sourceString, $arr2);       
        $c = $arr2[0];
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            $mima = mb_ord($c[$i]) + mb_ord($p[$i%$n]);
            $c[$i] = mb_chr($mima);
        }
        return implode('', $c);
    }

    /**
     *
     * @param sourceString
     * @param password
     * @return 明文
     */
    public static function decrypt2($sourceString, $password) {
        $p = str_split($password);
        $n = count($p);
        preg_match_all("/./u", $sourceString, $arr2);       
        $c = $arr2[0];
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            $mima = mb_ord($c[$i]) - mb_ord($p[$i%$n]);
            $c[$i] = mb_chr($mima);
        }
        return implode('', $c);
    }
}
