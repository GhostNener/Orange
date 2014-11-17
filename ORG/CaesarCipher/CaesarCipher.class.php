<?php
/**
* 凯撒加密解密算法
* @author Cinwell
*/
class CaesarCipher
{
	private static $UC_ENCRYPT_CHARS = array('M', 'D', 'X', 'U', 'P', 'I', 'B', 'E', 'J', 'C', 'T', 'N', 'K', 'O', 'G', 'W', 'R', 'S', 'F', 'Y', 'V', 'L', 'Z', 'Q', 'A', 'H' );
	private static $LC_ENCRYPT_CHARS = array('m', 'd', 'x', 'u', 'p', 'i', 'b', 'e', 'j', 'c', 't', 'n', 'k', 'o', 'g', 'w', 'r', 's', 'f', 'y', 'v', 'l', 'z', 'q', 'a', 'h');

	static {  
        for ($i = 0; $i < 26; $i++) {  
             $b = $UC_ENCRYPT_CHARS[$i];
             $UC_DECRYPT_CHARS[$b-'A'] = ('A'+$i);
        }  
    } 
}