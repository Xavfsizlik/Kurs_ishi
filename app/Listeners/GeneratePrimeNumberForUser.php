<?php

namespace App\Listeners;

use App\Models\Prime;
use App\Events\UserRegistered;
use App\Http\Controllers\PrimeController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\PrimeNumberGenerator;
use Illuminate\Support\Facades\Log;

class GeneratePrimeNumberForUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event)
    {

        Log::info('UserRegistered event triggered for user ID: ' . $event->user->id);

        $user = $event->user;

        // Tub son yaratish funksiyasi
            $prime1 = PrimeNumberGenerator::generatePrime();
            $prime2 = PrimeNumberGenerator::generatePrime();
            $product = gmp_mul($prime1, $prime2);

            Prime::create([
                'user_id' => $user->id,
                'prime' => gmp_strval($product),  // GMP qiymatini stringga aylantiring
            ]);

            self::downloadPrime($product);

    }

    public static function downloadPrime($request)
    {
        // Tub sonni olish
        $primeNumber = $request->input('prime_number');

        // Fayl nomi va mazmunini aniqlash
        $fileName = 'prime_number.txt';
        $content = $primeNumber;

        // Faylni vaqtinchalik saqlash
        $filePath = storage_path("app/public/{$fileName}");
        file_put_contents($filePath, $content);

        // Faylni yuklash
        return response()->download($filePath)->deleteFileAfterSend();
    }
}
