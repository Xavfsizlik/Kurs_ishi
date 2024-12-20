<x-layouts.app>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
    
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
    
                        {{-- <h1>Tub son</h1>
                        <h6>{{$data->prime ?? "prime topilmadi"}}</h6> --}}
                        @if (!@empty($data->prime))
                            @if(!@empty($kalit->user->name))
                                <h4>{{ $kalit->user->name }}</h4>
                                <h3>{{ $kalit->cipher }}</h3>
                                <div class="card-body text-center">
                                    <div class="alert alert-success" style="white-space: pre-wrap; word-wrap: break-word;">
                                        <h2>Shifrlangan kalit: {{ $yopiq_kalit }}</h2>
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <div class="alert alert-success" style="white-space: pre-wrap; word-wrap: break-word;">
                                        {{-- <h4>{{ $fiat ?? "" }}</h4> --}}
                                        <h2>Sizning kalitingiz: {{$ochiq_kalit}}</h2>
                                    </div>
                                </div>
                                <form action="{{ route('download.kalit') }}" method="POST">
                                    @csrf
                                    {{-- <p name="kalit_down">{{ $kalit->kalit }}</p> --}}
                                    <input type="hidden" name="kalit_down" value="{{ json_encode([$ochiq_kalit, $kalit->user->name]) }}">
                                    <button type="submit" class="btn btn-primary mt-3">Kalitni yuklash</button>
                                </form>
                            @endif
                        @endif
                       @if(!@empty($xato))
                            <h3>{{$xato}}</h3>
                       @endif
    
                        <!-- Tugma qo'shish -->
                        {{-- <form action="{{ route('download.prime') }}" method="POST">
                            @csrf
                            @if (!@empty($data->prime))
                                <input type="hidden" name="prime_number" value="{{ $data->prime}}">
                                <button type="submit" class="btn btn-primary mt-3">Tub sonni yuklash</button>
                            @endif
                        </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
    