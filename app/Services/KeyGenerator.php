<?php

namespace App\Services;

use App\Models\Cipher;
use App\Services\gmp_import;
use Exception;
use RuntimeException;
use GMP;
use Illuminate\Support\Facades\Auth;

class KeyGenerator
{
    public static function  encryptData($data, $key) {
        $method = 'AES-256-CBC'; // Shifrlash algoritmi'/dev/urandom'
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)); // Tasodifiy IV
        $encryptedData = openssl_encrypt($data, $method, $key, 0, $iv);
        return base64_encode($iv . $encryptedData); // IV va shifrlangan ma'lumotlarni birlashtirish
    }
    
    public static function  decryptData($encryptedData, $key) {
        $method = 'AES-256-CBC';
        $data = base64_decode($encryptedData); // Base64 ni dekod qilish
        $ivLength = openssl_cipher_iv_length($method);
        $iv = substr($data, 0, $ivLength); // IV ni '/dev/urandom'ajratish
        $encryptedData = substr($data, $ivLength); // Shifrlangan ma'lumotni ajratish
        return openssl_decrypt($encryptedData, $method, $key, 0, $iv); // Deshifrlash
    }
    
    /**
     * AES256 uchun kalit yaratish
     * AES256 32 baytlik kalit talab qiladi.
     */
    public static function generateAES256Key(): string
    {
        $bytes = file_get_contents('/dev/urandom', false, null, 0, 32);

        // Tasodifiy sonni 16-lik formatga aylantirish
        $hexValue = bin2hex($bytes);

        // dd(str_split($decimalValue));
        return $hexValue;
    }

    /**
     * AES192 uchun kalit yaratish
     * AES192 24 baytlik kalit talab qiladi.
     */
    public static function generateAES192Key(): string
    {
        $bytes = file_get_contents('/dev/urandom', false, null, 0, 24);

        // Tasodifiy sonni 16-lik formatga aylantirish
        $hexValue = bin2hex($bytes);
        
        // dd(str_split($decimalValue));
        return $hexValue;
    }

    /**
     * AES128 uchun kalit yaratish
     * AES128 16 baytlik kalit talab qiladi.
     */
    public static function generateAES128Key(): string
    {
        $bytes = file_get_contents('/dev/urandom', false, null, 0, 16);

        // Tasodifiy sonni 16-lik formatga aylantirish
        $hexValue = bin2hex($bytes);
        
        // dd(str_split($decimalValue));
        return $hexValue;
    }

    /**
     * RSA2048 uchun ochiq va yopiq kalit juftligi yaratish
     */
    public static function generateRSA2048KeyPair($p,$q): array
    {
        // GMP bilan ishlash uchun p va q ni tashqi ko'rinishda kiritamiz
        $p = gmp_init($p); // Birinchi tub son
        $q = gmp_init($q); // Ikkinchi tub son

        // n = p * q
        $n = gmp_mul($p, $q);

        // φ = (p - 1) * (q - 1)
        $phi = gmp_mul(
            gmp_sub($p, 1),
            gmp_sub($q, 1)
        );

        // e = 65537 (standart ochiq eksponent)
        $e = gmp_init(65537);

        // d = e⁻¹ mod φ (yopiq kalit)
        $d = gmp_invert($e, $phi);
        if ($d === false) {
            throw new Exception("e va φ bir-biriga nisbatan tub bo'lishi kerak.");
        }

        // Ochiq va yopiq kalitlarni qaytarish
        return [
            'public_key' => [
                'e' => gmp_strval($e),
                'n' => gmp_strval($n),
            ],
            'private_key' => [
                'd' => gmp_strval($d),
                'n' => gmp_strval($n),
            ],
        ];
    }

    /**
     * 3DES uchun kalit yaratish
     * 3DES 24 baytlik kalit talab qiladi.
     */
    public static function generate3DESKey(): string
    {
        $bytes = file_get_contents('/dev/urandom', false, null, 0, 24);

        // Tasodifiy sonni 16-lik formatga aylantirish
        $hexValue = bin2hex($bytes);
        
        // dd(str_split($decimalValue));
        return $hexValue;
    }

    /**
     * ChaCha20 uchun kalit yaratish
     * ChaCha20 32 baytlik kalit talab qiladi.
     */
    public static function generateChaCha20Key(): string
    {
        $bytes = file_get_contents('/dev/urandom', false, null, 0, 32);

        // Tasodifiy sonni 16-lik formatga aylantirish
        $hexValue = bin2hex($bytes);
        
        // dd(str_split($decimalValue));
        return $hexValue;
    }

    /**
     * A5/1 uchun kalit yaratish
     * A5/1 64 bit (8 bayt) talab qiladi.
     */
    public static function generateA5Key(): string
    {
        $bytes = file_get_contents('/dev/urandom', false, null, 0, 8);

        // Tasodifiy sonni 16-lik formatga aylantirish
        $hexValue = bin2hex($bytes);
        
        // dd(str_split($decimalValue));
        return $hexValue;
    }

    /**
     * Rabin algoritmi uchun kalit juftligi yaratish
     */
    public static function generateRabinKeyPair(array $primes): array
    {
        if (count($primes) < 2) {
            throw new Exception("Rabin uchun kamida 2 ta tub son kerak.");
        }

        $p = $primes[0]; // Tub sonlardan birini tanlash
        $q = $primes[1]; // Ikkinchisini tanlash

        if ($p % 4 !== 3 || $q % 4 !== 3) {
            throw new Exception("Tub sonlar 4k+3 ko'rinishida bo'lishi kerak.");
        }

        $n = $p * $q; // Modulus
        return ['public_key' => $n, 'private_key' => ['p' => $p, 'q' => $q]];
    }
}
