<?php
namespace Toolbox\Helper;

use Zend\View\Helper\AbstractHelper;

class Decrypt extends AbstractHelper
{
    public function __invoke($data)
    {
        $data = trim($data);
        if (empty($data)) {
            return '';
        }

        if (extension_loaded('mcrypt')) {
            $key = pack('H*', "b1a38e4bf7177f660abfd420be0be8b");
            $decodeText = base64_decode($data);
            $ivDec = substr($decodeText, 0, '16');
            $ciphertextDec = substr($decodeText, '16');        
            $text = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertextDec, MCRYPT_MODE_CBC, $ivDec);
            
            return $text;
        }

        return $data;
    }
}