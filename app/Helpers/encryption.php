<?php

namespace App\Helpers;

class encryption
{
   public static function encrypt($plaintext)
   {
      if (!$plaintext) return null;

      $cipher = config("encryption.cipher");
      $key = config("encryption.key");
      $iv = config("encryption.iv");
      $ciphertext = "";

      if (in_array(strtolower($cipher), openssl_get_cipher_methods())) {
         $ciphertext = openssl_encrypt(data: $plaintext, cipher_algo: $cipher, passphrase: $key, iv: $iv);
      } else {
         return false;
      }
      return $ciphertext ?: null;
   }

   public static function decrypt($ciphertext)
   {
      if (!$ciphertext) return null;

      $cipher = config("encryption.cipher");
      $key = config("encryption.key");
      $iv = config("encryption.iv");
      $original_plaintext = "";

      if (in_array(strtolower($cipher), openssl_get_cipher_methods())) {
         $original_plaintext = openssl_decrypt(data: $ciphertext, cipher_algo: $cipher, passphrase: $key, iv: $iv);
      } else {
         return false;
      }
      return $original_plaintext ?: null;
   }
}