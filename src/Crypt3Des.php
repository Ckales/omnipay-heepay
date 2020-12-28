<?php
/**
 * Created by PhpStorm.
 * User: ChingLi
 * Date: 2016/12/7 0007
 * Time: 下午 2:51
 */

namespace Omnipay\Heepay;


class Crypt3Des
{
    public $key = "";

    /**
     * 数据加密
     * @param $input string 需要加密的字符串
     * @return string
     */
    public function encrypt($input)
    { // 数据加密
        if (empty($input)) {
            return null;
        }
        $input = $this->pkcs5_pad($input, 8);
        $key = str_pad($this->key, 24, '0');

        $data = openssl_encrypt($input, "DES-EDE3", $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);
        return $this->strToHex($data);
    }

    /**
     * 数据解密
     * @param $encrypted string 需要解密的字符串
     * @return string
     */
    public function decrypt($encrypted)
    { // 数据解密
        if (!$encrypted || empty($encrypted)) {
            return null;
        }

        $encrypted = $this->hexToStr($encrypted);

        if (!$encrypted || empty($encrypted)) {
            return null;
        }

        $key = str_pad($this->key, 24, '0');
        $decrypted = openssl_decrypt($encrypted, "DES-EDE3", $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);
        return $this->pkcs5_unpad($decrypted);
    }

    function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad($text)
    {
        $pad = ord($text[strlen($text) - 1]);
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }

    function strToHex($string)
    {
        $hex = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $iHex = dechex(ord($string[$i]));
            if (strlen($iHex) == 1)
                $hex .= '0' . $iHex;
            else
                $hex .= $iHex;
        }
        $hex = strtoupper($hex);
        return $hex;
    }

    function hexToStr($hex)
    {
        $string = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }
}