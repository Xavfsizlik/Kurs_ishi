<x-layouts.app>
    <x-slot:title>
        Key
    </x-slot:title>

    <!-- Foydalanuvchi ma'lumotlari -->
    <h1>{{ $data ?? 'Bu foydalanuvchi bilan umumiy algoritmga ega emassiz!' }}</h1>
    <h2>{{ $seetid->name ?? "" }}</h2>
    <!-- Natija va Fiat -->
    <div class="card-body text-center">
        <div class="alert alert-success" style="white-space: pre-wrap; word-wrap: break-word;">
            <h4>{{ $fiat ?? "" }}</h4>
        </div>
    </div>

    <!-- Kalit ma'lumotlari -->
    @if ($data == 'RSA' && $data != 'Kalit yo\'q')
        {{-- <h2>Shifrlangan kalit: {{ $aescipher['public_key'] }}</h2> --}}
        <div class="card-body text-center">
            <div class="alert alert-success" style="white-space: pre-wrap; word-wrap: break-word;">
                {{-- <h2>Shifrlangan kalit: {{ $aescipher }}</h2> --}}
                <h2>Sizning kalitingiz: e:{{$keyasl['public_key']['e']}}</h2>
                <h4> n:{{$keyasl['public_key']['n']}}</h4>
            </div>
        </div>
    @else
    <div class="card-body text-center">
        <div class="alert alert-success" style="white-space: pre-wrap; word-wrap: break-word;">
            <h2>Shifrlangan kalit: {{ $aescipher }}</h2>
        </div>
    </div>
    <div class="card-body text-center">
        <div class="alert alert-success" style="white-space: pre-wrap; word-wrap: break-word;">
            {{-- <h4>{{ $fiat ?? "" }}</h4> --}}
            <h2>Sizning kalitingiz: {{$keyasl}}</h2>
        </div>
    </div>
    @endif

    <!-- Form yuborish (Kalitlarni yuklab olish) -->
    @if (!empty($data) && $data != 'Prime mos kelmadi')
        <form action="{{ route('download.key') }}" method="POST">
            @csrf
            @if ($data == 'RSA')
                <input type="hidden" name="key" value="{{ json_encode([$keyasl['private_key'], $data, $seetid]) }}">
                <button type="submit" class="btn btn-primary mt-3">Kalitni yuklash</button>
            @else
                <input type="hidden" name="key" value="{{ json_encode([$keyasl, $data, $seetid]) }}">
                <button type="submit" class="btn btn-primary mt-3">Kalitni yuklash</button>
            @endif
            
        </form>
    @endif
</x-layouts.app>
    