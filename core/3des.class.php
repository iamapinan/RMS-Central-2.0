<?php
class TripleDES {
	public static function genIvParameter() {
		//echo 'MCRYPT_RAND=' .mcrypt_get_iv_size(MCRYPT_TRIPLEDES,MCRYPT_MODE_CBC) . "\n";
		return mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES,MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
	}

	private static function pkcs5Pad($text, $blocksize) {
	    $pad = $blocksize - (strlen($text) % $blocksize); // in php, strlen returns the bytes of $text
	    return $text . str_repeat(chr($pad), $pad);
	}

	private static function pkcs5Unpad($text) {
	    $pad = ord($text{strlen($text)-1});
	    if ($pad > strlen($text)) return false;
	    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
	    return substr($text, 0, -1 * $pad);
	}

	public static function encryptText($plain_text, $key, $iv) {
	    $padded = TripleDES::pkcs5Pad($plain_text, mcrypt_get_block_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_CBC));
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $padded, MCRYPT_MODE_CBC, $iv);
	}

	public static function decryptText($cipher_text, $key, $iv) {
	    $plain_text = mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $cipher_text, MCRYPT_MODE_CBC, $iv);
	    return TripleDES::pkcs5Unpad($plain_text);
	}
};