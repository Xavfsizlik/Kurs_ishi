<?php

namespace App\Http\Controllers;

use App\Models\Kalit;
use App\Models\Prime;
use App\Services\KeyGenerator;
use App\Services\PrimeNumberGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrimeController extends Controller
{
    public function generatePrime(Request $request)
    {
        // dd($request);
        $data = Auth::user()->prime;
        $idsi=Auth::user()->id;

        $fileName = 'file:///home/samariddin/Downloads/prime_number.txt';
        if (file_exists($fileName)) {
            $fileContents = file_get_contents($fileName);
            $primeNumber = gmp_init(trim($fileContents), 10);
            // Faylning mazmunini ko'rsatish yoki ishlatish
            
        } else {
            $result = 'Fayl topilmadi';
            Auth::logout(); // Foydalanuvchini tizimdan chiqarish

            // Sessionni tozalash (xavfsizlik uchun)
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with([
                'data'=>$data,
                'xato'=>$result,
            ]);
        }
        
        $dataprime=Auth::user()->prime;
        // dd($secondUser->name);
        if((string)$dataprime->prime !== gmp_strval($primeNumber)){
            Auth::logout(); // Foydalanuvchini tizimdan chiqarish

            // Sessionni tozalash (xavfsizlik uchun)
            $request->session()->invalidate();
            $request->session()->regenerateToken();
                // dd('salo');
            $result = 'Autentifikatsiyadan o\'tmadi.';
            // Foydalanuvchini login sahifasiga qaytarish
        return redirect('/')->with([
           'xato' => $result,
        ]);
            
    
        }


            $fiat = PrimeNumberGenerator::FiatShamir($primeNumber);
            $randomNumber = PrimeNumberGenerator::generate256BitHex(); // 64 ta hexadecimal belgi (256 bit)
            $result = (PrimeNumberGenerator::AESkey($randomNumber,$fiat));//Almashinish uchun AES256 kaliti
            $kalit = Kalit::where('myuser_id', $idsi)->first();
            // dd($kalit);
            if(!empty($kalit)){

                $privatekey = KeyGenerator::encryptData($kalit->kalit,$result);
                $publickey = KeyGenerator::decryptData($privatekey,$result);
                // dd($kalit);
                return view('primes')->with([
                    'data'=>$data,
                    'kalit' => $kalit,
                    "yopiq_kalit" => $privatekey,
                    'ochiq_kalit' => $publickey,
                ]);
            }else{
                return view('primes')->with([
                    'data' => $data,
                    'kalit' => '',
                    "yopiq_kalit" => '',
                    'ochiq_kalit' => '',
                ]);
            }
    }

    public static function downloadPrime(Request $request)
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

    public function downloadKey(Request $request)
    {
        $keys = json_decode($request->input('key'), true);
        if (!$keys || count($keys) < 3) {
            return back()->with('error', 'Kalit ma\'lumotlari noto\'g\'ri.');
        }
        [$key, $name, $seetid] = $keys;
        // dd($key);
        if($name == 'RSA'){
            $data=Kalit::create(([
                'user_id'=>Auth::user()->id,
                'myuser_id'=>$seetid,
                'kalit'=>'d:'.$key['d']."\nn:".$key['n'],
                'cipher'=>$name
            ]));
            $primeNumber = 'd:'.$key['d']."\nn:".$key['n'];
        }else{
            $data=Kalit::create(([
                'user_id'=>Auth::user()->id,
                'myuser_id'=>$seetid,
                'kalit'=>$key,
                'cipher'=>$name
            ]));
            $primeNumber = $key;
        }
        // Tub sonni olish

        // Fayl nomi va mazmunini aniqlash
        $fileName = Auth::user()->name.'_'.$name.'_key.txt';
        $content = $primeNumber;

        // Faylni vaqtinchalik saqlash
        $filePath = storage_path("app/public/{$fileName}");
        file_put_contents($filePath, $content);

        // Faylni yuklash
        return response()->download($filePath)->deleteFileAfterSend();
    }

    public function downloadKalit(Request $request)
    {
        
        // Fayl yo'li
        

        $filePath = '/home/samariddin/Downloads/prime_number.txt';
        $fileContents = file_get_contents($filePath);
        $primeNumber = trim($fileContents); // Ortiqcha bo'sh joylarni olib tashlash

        // Bazada prime mavjudligini tekshirish
        $primeRecord = Prime::where('prime', $primeNumber)->first();
        if (!$primeRecord) {
            $result = 'Autentifikatsiyadan o\'tmadi.';
            return view('primes')->with(['xato' => $result]);
        }

        $keys = json_decode($request->input('kalit_down'), true);
        // dd($keys);
        if (!$keys || count($keys) < 2) {
            return back()->with('error', 'Kalit ma\'lumotlari noto\'g\'ri.');
        }
        [$key, $name] = $keys;
        // Fayl mavjudligini tekshirish
        if (!file_exists($filePath)) {
            $result = 'Fayl topilmadi';
            return view('prime')->with(['xato' => $result]);
        }
        // Kalitni olish
       
        if (empty($key)) {
            $result = 'Kalit bo\'sh';
            return view('primes')->with(['xato' => $result]);
        }

        // Faylni saqlash uchun ma'lumot tayyorlash
        $fileName = $name.'->'.Auth::user()->name . '_key.txt';
        $content = $key;
        // Faylni vaqtinchalik saqlash
        $tempFilePath = storage_path("app/public/{$fileName}");
        file_put_contents($tempFilePath, $content);

        // Faylni yuklash
        $response = response()->download($tempFilePath)->deleteFileAfterSend();

        // Kalitni ma'lumotlar bazasidan o'chirish
        $kalitRecord = Kalit::where('kalit', $key)->first();
        if ($kalitRecord) {
            $kalitRecord->delete(); // Kalit jadvalidagi mos yozuvni o'chirish
        }

        return $response;
    }

    
}
