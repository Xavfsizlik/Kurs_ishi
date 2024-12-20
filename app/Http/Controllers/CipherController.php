<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCipherRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Cipher;
use App\Models\User;
use App\Services\KeyGenerator;
use App\Services\PrimeNumberGenerator;
use Illuminate\Http\Request;

class CipherController extends Controller
{
    public function animatsiya(){
        return view('animatsiya');
    }

    public function index(){
        $user_names=User::get('name');
        return view('cipher')->with([
            'ciphers' => Cipher::all(),
            'names'=>$user_names
        ]);
    }
    public function create(Request $request){
        return view('cipher')->with([
            'ciphers' => Cipher::all(),
        ]);
    }
    
    public function store(StoreCipherRequest $request){
         // Hozirgi foydalanuvchini olish (agar autentifikatsiya qilingan bo'lsa)
         $user = $request->user();

         // Formadan kelgan shifrlash usullari ID-larini olish
         if(!empty($request->ciphers))
         {
             $cipherIds = $request->input('ciphers');
             
             if ($user && $cipherIds) {
                 // Foydalanuvchiga tanlangan shifrlash usullarini bog'lash
                 $user->ciphers()->sync($cipherIds); // Sync eski ma'lumotlarni o'chiradi va yangisini bog'laydi
                 //dd($request);
                 return redirect()->route('about');
                }
        }
        
         return redirect()->route('about');
     }
    
     public function shirkat(Request $request)
    {
        //dd(Cipher::find($request->id));
        $firstUser = $request->user();
        $secondUser = User::find($request->id);
        if (empty($secondUser)) {
            return response()->json(['error' => 'user not font'], 404);
        }
        // dd($secondUser);
        $data = Cipher::whereHas('users', function ($query) use ($firstUser) {
            $query->where('user_id', $firstUser->id);
        })->whereHas('users', function ($query) use ($secondUser) {
            $query->where('user_id', $secondUser->id);
        })->orderByDesc('daraja') // daraja bo‘yicha saralash
        ->first();
        //  dd($data);
        $asd = $secondUser->id;
        $fileName = 'file:///home/samariddin/Downloads/prime_number.txt';
        if (file_exists($fileName)) {
            $fileContents = file_get_contents($fileName);
            $primeNumber = gmp_init(trim($fileContents), 10);
            // Faylning mazmunini ko'rsatish yoki ishlatish
            
        } else {
            $result = 'Fayl topilmadi';
            return view('keycreate')->with([
                'data'=>$data->name,
                'result'=>$result,
            ]);
        }
        
        $dataprime=Auth::user()->prime;
        // dd($secondUser->name);
        if((string)$dataprime->prime !== gmp_strval($primeNumber)){
            $result = 'Autentifikatsiyadan o\'tmadi.';
            // dd([$dataprime->prime,$primeNumber]);
            return view('keycreate')->with([
                    'data'=> $result,//Qaysi shifrlash ekanligi
                    'aeskait'=>'',//Almashinish uchun AES256 kaliti
                    'aescipher'=> '',//shifrlangan kalit
                    'fiat' => '',//fiat-shamir orqali hosil bo'lgan son
                    'seetid' => '',//Qaysi userga yurolilishini bilish uchun user
                    'keyasl'=> '',//Yashirin kalit
                
            ]);
        }
        // dd($data);
        $xabarkalit='';
        if(!empty($data)){
            $kim = $data->name;
            $fiat = PrimeNumberGenerator::FiatShamir($primeNumber);
            $randomNumber = PrimeNumberGenerator::generate256BitHex(); // 64 ta hexadecimal belgi (256 bit)
            $result = (PrimeNumberGenerator::AESkey($randomNumber,$fiat));//Almashinish uchun AES256 kaliti
            if($data->name == 'RSA'){
                $prime1 = PrimeNumberGenerator::generatePrime();
                $prime2 = PrimeNumberGenerator::generatePrime();
                $xabar = KeyGenerator::generateRSA2048KeyPair($prime1,$prime2);
                $e = KeyGenerator::encryptData($xabar['public_key']['e'],$result);
                $n = KeyGenerator::encryptData($xabar['public_key']['n'],$result);
                $d = KeyGenerator::encryptData($xabar['private_key']['d'],$result);
                $xabarkalit=[
                    ['public_key' => 'e:'.$e."\n".'n:'.$n],
                    ['private_key' => 'd:'.$d."\n".'n:'.$n],
                ];
                return view('keycreate')->with([
                    'data'=> $data->name,//Qaysi shifrlash ekanligi
                    'aeskait'=>$result,//Almashinish uchun AES256 kaliti
                    'aescipher'=> $xabarkalit,//shifrlangan kalit
                    'fiat' => $fiat,//fiat-shamir orqali hosil bo'lgan son
                    'seetid' => $asd,//Qaysi userga yurolilishini bilish uchun user
                    'keyasl'=> $xabar,//Yashirin kalit
                ]); 
            }
            if($data->name == 'AES128'){
                $xabarkalit = KeyGenerator::generateAES128Key();
                // dd($result);
                
            }
            if($data->name == 'AES192'){
                $xabarkalit = KeyGenerator::generateAES192Key();
                // dd($result);
                
            }
            if($data->name == 'AES256'){
                $xabarkalit = KeyGenerator::generateAES256Key();
                //dd($result);
               
            }
            if($data->name == 'A5/1'){
                $xabarkalit = KeyGenerator::generateA5Key();
                // dd($result);
                
            }
            if($data->name == 'ChaCha20'){
                $xabarkalit = KeyGenerator::generateChaCha20Key();
                // dd($xabarkalit);
                
            }
            if($data->name == '3DES'){
                $xabarkalit = KeyGenerator::generate3DESKey();
                // dd($result);
                
            }
            $shifr = KeyGenerator::encryptData($xabarkalit,$result);
        }else{
            $kim= null;
            $result='-';//Almashinish uchun AES256 kaliti
            $fiat='-';//fiat-shamir orqali hosil bo'lgan son
            $shifr = '-';
            $asd='-';//Qaysi userga yurolilishini bilish uchun user
            $xabarkalit='-';//Yashirin kalit
        }
        // $shifr=KeyGenerator::encryptData($xabarkalit,$result);
        // $deshifr=KeyGenerator::decryptData($shifr,$result);
        // dd([
        //     $xabarkalit,
        //     $shifr,
        //     $deshifr,
        // ]);
        return view('keycreate')->with([
            'data'=> $kim,//Qaysi shifrlash ekanligi
            'aeskait'=>$result,//Almashinish uchun AES256 kaliti
            'aescipher'=> $shifr,//shifrlangan kalit
            'fiat' => $fiat,//fiat-shamir orqali hosil bo'lgan son
            'seetid' => $asd,//Qaysi userga yurolilishini bilish uchun user
            'keyasl'=> $xabarkalit,//Yashirin kalit
        ]);
    }

    public function searchUsers(Request $request)
    {
        $authUserId = Auth::id();
        $query = $request->input('query', ''); // Qidiruv so'zini oling
        $users = User::where('id', '!=', $authUserId)
                    ->where('name', 'LIKE', "%{$query}%") // Qidiruvni qo'shing
                    ->get(['id', 'name']);
        return response()->json($users); // Foydalanuvchilarni qaytaring
    }
    public function foydalanuvchi(){
        $authUserId = Auth::id();
        $users = User::where('id', '!=', $authUserId)->get(['id','name']);
        return view('key')->with([
            'users'=>$users
        ]);
    }
}