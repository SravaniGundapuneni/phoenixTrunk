<?php
namespace Toolbox\Helper;

use Zend\View\Helper\AbstractHelper;

class Encrypt extends AbstractHelper
{
    public function __invoke($data)
    {
        $data = trim($data);
        if (empty($data)) {
            return '';
        }

        if (extension_loaded('mcrypt')) {
            $key = pack('H*', "b1a38e4bf7177f660abfd420be0be8b");
            $iv = mcrypt_create_iv('16', MCRYPT_RAND);
            $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
            $ciphertext = $iv . $ciphertext;
            $ciphertext_base64 = base64_encode($ciphertext);
            return $ciphertext_base64;            
        }

        return $data;
    }
}