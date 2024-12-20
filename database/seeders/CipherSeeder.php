<?php

namespace Database\Seeders;

use App\Models\Cipher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CipherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cipher::create(['name'=>'AES128','daraja'=>80]);
        Cipher::create(['name'=>'AES192','daraja'=>85]);
        Cipher::create(['name'=>'AES256','daraja'=>100]);
        Cipher::create(['name'=>'3DES','daraja'=>86]);
        Cipher::create(['name'=>'A5/1','daraja'=>78]);
        Cipher::create(['name'=>'RSA','daraja'=>95]);
        Cipher::create(['name'=>'ChaCha20','daraja'=>92]);
    }
}
