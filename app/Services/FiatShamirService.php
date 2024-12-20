<?php

namespace App\Services;

use GMP;

class FiatShamirService
{
    private $p;
    private $q;
    private $n;
    private $s; // Secret key
    private $v; // Public key

    public function __construct()
    {
        $this->p = $this->generatePrime(2048); // 2048-bit prime
        $this->q = $this->generatePrime(2048);
        $this->n = gmp_mul($this->p, $this->q);
        $this->s = gmp_random_range(1, $this->n); // Secret key
        $this->v = gmp_mod(gmp_pow($this->s, 2), $this->n); // Public key
    }

    public function getPublicKey()
    {
        return [
            'n' => gmp_strval($this->n),
            'v' => gmp_strval($this->v),
        ];
    }

    public function getChallenge()
    {
        return random_int(0, 1);
    }

    public function verify($c, $r, $y)
    {
        if ($c == 0) {
            return gmp_mod(gmp_pow($r, 2), $this->n) == $y;
        } elseif ($c == 1) {
            return gmp_mod(gmp_pow($r, 2), $this->n) == gmp_mod(gmp_mul($y, $this->v), $this->n);
        }
        return false;
    }

    private function generatePrime($bits)
    {
        do {
            $num = gmp_random_bits($bits);
            if (gmp_prob_prime($num, 15) > 0) {
                return $num;
            }
        } while (true);
    }
}
