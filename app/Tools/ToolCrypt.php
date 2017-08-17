<?php
namespace App\Tools;
/**
 * 功能:可逆加密函数  UTF-8编码
 * 用法:
 * $txtStream = 'xxxxxxxxxxxxffffffffffffffffffffff||205001';
   $out = CryptUtils::enCrypt($txtStream);
   print_r($out);
   $in = CryptUtils::deCrypt($out);
   print_r($in);
 */

class ToolCrypt{
    static $password = 'Sunfund_public_wuliu_ver_0.1';

    static $lockstream = 'stlDEFABCNOPyzghijQRSTUwxkVWXYZabcdefIJK67nopqr89LMmGH012345uv';

    /**
     * @param $txtStream
     * @return string
     */
    public static function enCrypt($txtStream){
        $password = self::$password;
        $lockLen = strlen(self::$lockstream);
        $lockCount = rand(0,$lockLen-1);
        $randomLock = self::$lockstream[$lockCount];
        $password = md5($password.$randomLock);
        $txtStream = base64_encode($txtStream);
        $tmpStream = '';
        $i=0;$j=0;$k = 0;
        for ($i=0; $i<strlen($txtStream); $i++) {
            $k = $k == strlen($password) ? 0 : $k;
            $j = (strpos(self::$lockstream,$txtStream[$i])+$lockCount+ord($password[$k]))%($lockLen);
            $tmpStream .= self::$lockstream[$j];
            $k++;
        }
        return $tmpStream.$randomLock;
    }


    /**
     * @param $txtStream
     * @return string
     */
    public static function deCrypt($txtStream){
        $password = self::$password;
        $lockLen = strlen(self::$lockstream);
        $txtLen = strlen($txtStream);
        $randomLock = $txtStream[$txtLen - 1];
        $lockCount = strpos(self::$lockstream,$randomLock);
        $password = md5($password.$randomLock);
        $txtStream = substr($txtStream,0,$txtLen-1);
        $tmpStream = '';
        $i=0;$j=0;$k = 0;
        for ($i=0; $i<strlen($txtStream); $i++) {
            $k = $k == strlen($password) ? 0 : $k;
            $j = strpos(self::$lockstream,$txtStream[$i]) - $lockCount - ord($password[$k]);
            while($j < 0){
                $j = $j + ($lockLen);
            }
            $tmpStream .= self::$lockstream[$j];
            $k++;
        }
        return base64_decode($tmpStream);
    }
}
?>