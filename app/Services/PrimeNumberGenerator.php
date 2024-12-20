<?php

namespace App\Services;

use App\Models\Cipher;
use App\Services\gmp_import;
use Exception;
use RuntimeException;
use GMP;
use Illuminate\Support\Facades\Auth;

class PrimeNumberGenerator
{
    /**
     * 2048-bitli tub son yaratish
     *
     * @param int $bitLength
     * @throws RuntimeException
     */
    public static function generatePrime(int $bitLength = 2048)
    {
        $son=0;
        do {
            // Tasodifiy 2048 bitli son yaratish
            $bytes = file_get_contents('/dev/urandom', false, null, 0, $bitLength / 8);
            if ($bytes === false) {
                throw new RuntimeException("Urandom'dan o'qib bo'lmadi.");
            }

            // Tasodifiy baytlarni GMP songa aylantirish
            $randomNumber = \gmp_import($bytes);
            $son=$son+1;
            // Toq qilish (oxirgi bitni 1 ga o'rnatish)
            gmp_setbit($randomNumber, 0);
        } while (!self::millerRabinTest($randomNumber,10)); // 10 iteratsiya orqali tublikka tekshirish
//gmp_prob_prime
        return $randomNumber; // GMP obyekt sifatida qaytadi
    }


    private static function millerRabinTest($n, $k)
    {
        // Agar n = 2 bo'lsa, to'g'ri bo'ladi
        if (gmp_cmp($n, 2) == 0) {
            return true;
        }

        // Agar n juft bo'lsa, soxta
        if (gmp_mod($n, 2) == 0) {
            return false;
        }

        // n - 1 = d * 2^r
        $d = gmp_sub($n, 1);
        $r = 0;
        while (gmp_mod($d, 2) == 0) {
            $d = gmp_div($d, 2);
            $r++;
        }

        // k marta sinov o'tkazish
        for ($i = 0; $i < $k; $i++) {
            // Tasodifiy a ni tanlash
            $a = gmp_add(gmp_random_range(2, gmp_sub($n, 2)), 1);
            // a^d mod n ni hisoblash
            $x = gmp_powm($a, $d, $n);

            // Agar x == 1 yoki x == n - 1 bo'lsa, bu testni o'tgan hisoblanadi
            if (gmp_cmp($x, 1) == 0 || gmp_cmp($x, gmp_sub($n, 1)) == 0) {
                continue;
            }

            // r-1 marta x ni kvadratlash
            $isPrime = false;
            for ($j = 0; $j < $r - 1; $j++) {
                $x = gmp_powm($x, 2, $n);
                if (gmp_cmp($x, gmp_sub($n, 1)) == 0) {
                    $isPrime = true;
                    break;
                }
            }

            // Agar x == n - 1 bo'lmasa, n ni tub emas deb hisoblash
            if (!$isPrime) {
                return false;
            }
        }

        // Agar barcha testlar o'tgan bo'lsa, n tub deb hisoblanadi
        return true;
    }

    public static function generate256BitHex()
    {
        $bytes = file_get_contents('/dev/urandom', false, null, 0, 32);

        // Tasodifiy sonni 16-lik formatga aylantirish
        $hexValue = bin2hex($bytes);

        // 16-likni 10-lik formatga aylantirish va natijani stringda qaytarish
        $decimalValue = (string)hexdec($hexValue);
        // dd(str_split($decimalValue));
        return (string)$hexValue;
    }


    public static function FiatShamir($primeNumber){
         // 1. Tub sonlarni va boshqa asosiy kalitlarni generatsiya qilish         
         $n = $primeNumber;
 
         $s = gmp_random_range(1, gmp_sub($n, 1)); // Maxfiy kalit
         $v = gmp_mod(gmp_pow($s, 2), $n); // Ochiq kalit
 
         // 2. Mijoz (tasodifiy r va x)
         $r = gmp_random_range(1, gmp_sub($n, 1));
         $x = gmp_mod(gmp_pow($r, 2), $n);
 
         // 3. Server so'rovi (c)
         $c = random_int(0, 1); // Tasodifiy 0 yoki 1
 
         // 4. Mijoz javobi (y)
         if ($c === 0) {
             $y = gmp_mod($r, $n);
         } else {
             $y = gmp_mod(gmp_mul($r, $s), $n);
         }
 
         // 5. Server tekshiruvi
         $isValid = false;
         if ($c === 0) {
             $isValid = gmp_cmp(gmp_mod(gmp_pow($y, 2), $n), $x) === 0;
         } else {
             $isValid = gmp_cmp(gmp_mod(gmp_pow($y, 2), $n), gmp_mod(gmp_mul($x, $v), $n)) === 0;
         }
         $result = $isValid ? gmp_strval($y) : 'Autentifikatsiya muvaffaqiyatsiz!';
         return $result;
    }
    
    public static function AESkey($aes,$fiat){
        
    $fiat = gmp_init($fiat, 10); 
        // $aes uzunligini aniqlash
    $uzun = strlen($aes);

    // $fiat > 1 bo'lgunga qadar ishlash
    while (gmp_cmp($fiat, "1") > 0) {
        $index = gmp_intval(gmp_mod($fiat, 64));
        $fiat = gmp_div_q($fiat, 100);
            $newHexValue = gmp_strval(gmp_mod($fiat, 16),16);
            $aes[$index] = $newHexValue;
            $fiat = gmp_div_q($fiat, 64);
    }
    return $aes;
    }


}
